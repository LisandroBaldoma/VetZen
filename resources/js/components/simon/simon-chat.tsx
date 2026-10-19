import { useHttp } from '@inertiajs/react';
import { Bot, Send } from 'lucide-react';
import { useEffect, useState } from 'react';
import type { FormEvent } from 'react';
import AlertError from '@/components/alert-error';
import { EmptyState } from '@/components/empty-state';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import { cn } from '@/lib/utils';
import { store as storeMessage } from '@/routes/simon/messages';

export type SimonModule =
    | 'account'
    | 'appointments'
    | 'clients'
    | 'medical-records'
    | 'pets'
    | 'procedures'
    | 'service-requests'
    | 'services'
    | 'treatments';

type Source = {
    id: string;
    title: string;
    module: string | null;
};

type SimonResponse = {
    answer: string;
    sources: Source[];
};

type Message = {
    id: string;
    role: 'user' | 'assistant';
    content: string;
    sources?: Source[];
    pending?: boolean;
};

type Props = {
    module?: SimonModule;
    compact?: boolean;
};

export function SimonChat({ module, compact = false }: Props) {
    const { data, setData, submit, processing, errors, clearErrors } = useHttp<
        { question: string; module?: SimonModule },
        SimonResponse
    >({ question: '', module });
    const [messages, setMessages] = useState<Message[]>([]);
    const [composerError, setComposerError] = useState<string | null>(null);
    const [requestError, setRequestError] = useState<string | null>(null);

    useEffect(() => {
        setData('module', module);
    }, [module, setData]);

    const sendMessage = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        const question = data.question.trim();

        if (question === '') {
            setComposerError('Escribí una pregunta para Simón.');

            return;
        }

        const pendingId = crypto.randomUUID();
        let failureMessage =
            'Simón no pudo responder en este momento. Intentá nuevamente más tarde.';

        clearErrors();
        setComposerError(null);
        setRequestError(null);
        setMessages((current) => [
            ...current,
            { id: crypto.randomUUID(), role: 'user', content: question },
            { id: pendingId, role: 'assistant', content: '', pending: true },
        ]);

        try {
            const response = await submit(storeMessage(), {
                onHttpException: (response) => {
                    failureMessage =
                        response.status === 429
                            ? 'Alcanzaste el límite de preguntas. Esperá un momento antes de volver a intentarlo.'
                            : response.status === 503
                              ? 'Simón no está disponible en este momento. Intentá nuevamente más tarde.'
                              : failureMessage;
                },
                onNetworkError: () => {
                    failureMessage =
                        'No se pudo conectar con Simón. Revisá tu conexión e intentá nuevamente.';
                },
            });

            if (!response) {
                setMessages((current) =>
                    current.filter((message) => message.id !== pendingId),
                );

                return;
            }

            setMessages((current) =>
                current.map((message) =>
                    message.id === pendingId
                        ? {
                              ...message,
                              content: response.answer,
                              sources: response.sources,
                              pending: false,
                          }
                        : message,
                ),
            );
            setData('question', '');
        } catch {
            setMessages((current) =>
                current.filter((message) => message.id !== pendingId),
            );
            setRequestError(failureMessage);
        }
    };

    return (
        <div className="flex min-h-0 flex-1 flex-col">
            <div
                aria-live="polite"
                className={cn(
                    'flex min-h-0 flex-1 flex-col gap-5 overflow-y-auto',
                    compact ? 'bg-primary/3 p-4' : 'p-4 sm:p-6',
                )}
            >
                {messages.length === 0 ? (
                    <EmptyState
                        className="my-auto border-0 bg-transparent px-2 shadow-none"
                        icon={<Bot aria-hidden className="size-7" />}
                        title="¿Cómo puedo ayudarte?"
                        description="Preguntame cómo usar VetZen. No brindo diagnósticos, recomendaciones veterinarias ni indicaciones de medicamentos."
                    />
                ) : (
                    messages.map((message) => (
                        <article
                            key={message.id}
                            className={cn(
                                'text-sm leading-6 whitespace-pre-wrap',
                                compact
                                    ? 'max-w-[90%] rounded-2xl px-3.5 py-3'
                                    : 'max-w-2xl rounded-xl px-4 py-3',
                                message.role === 'user'
                                    ? 'ml-auto rounded-tr-sm bg-primary text-primary-foreground'
                                    : 'rounded-tl-sm border bg-card text-foreground shadow-xs',
                            )}
                        >
                            {message.pending ? (
                                <div className="flex items-center gap-2 text-muted-foreground">
                                    <Spinner aria-label="Simón está respondiendo" />
                                    <span>Simón está respondiendo...</span>
                                </div>
                            ) : (
                                <>
                                    <p>{message.content}</p>
                                    {message.sources &&
                                        message.sources.length > 0 && (
                                            <div className="mt-3 border-t border-border/70 pt-3">
                                                <p className="text-xs font-semibold tracking-wide text-muted-foreground uppercase">
                                                    Fuentes
                                                </p>
                                                <ul className="mt-1 space-y-1 text-xs text-muted-foreground">
                                                    {message.sources.map(
                                                        (source) => (
                                                            <li key={source.id}>
                                                                {source.title}
                                                                {source.module
                                                                    ? ` · ${source.module}`
                                                                    : ''}
                                                            </li>
                                                        ),
                                                    )}
                                                </ul>
                                            </div>
                                        )}
                                </>
                            )}
                        </article>
                    ))
                )}
            </div>

            <form
                onSubmit={sendMessage}
                className="border-t bg-card p-3 sm:p-4"
            >
                {requestError && (
                    <AlertError
                        title="No se pudo enviar la pregunta"
                        errors={[requestError]}
                    />
                )}
                <div className="mt-3 flex items-end gap-2">
                    <Textarea
                        value={data.question}
                        onChange={(event) => {
                            setData('question', event.target.value);
                            setComposerError(null);
                        }}
                        maxLength={1000}
                        disabled={processing}
                        aria-invalid={Boolean(composerError || errors.question)}
                        aria-describedby="simon-question-error simon-question-help"
                        placeholder="Preguntale a Simón..."
                        className={cn(
                            'resize-none',
                            compact ? 'min-h-24' : 'min-h-20',
                        )}
                    />
                    <Button
                        type="submit"
                        size="icon"
                        disabled={processing}
                        aria-label="Enviar pregunta a Simón"
                    >
                        {processing ? <Spinner /> : <Send />}
                    </Button>
                </div>
                <div className="mt-2 flex items-start justify-between gap-3 text-xs text-muted-foreground">
                    <p id="simon-question-help">
                        Simón responde sobre el uso de VetZen.
                    </p>
                    <span>{data.question.length}/1000</span>
                </div>
                {(composerError || errors.question) && (
                    <p
                        id="simon-question-error"
                        className="mt-2 text-sm text-destructive"
                    >
                        {composerError || errors.question}
                    </p>
                )}
            </form>
        </div>
    );
}
