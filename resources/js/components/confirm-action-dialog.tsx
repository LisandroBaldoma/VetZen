import type { ReactNode } from 'react';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

type Props = {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    onConfirm: () => void;
    title: string;
    description: ReactNode;
    content?: ReactNode;
    cancelLabel?: string;
    confirmLabel: string;
    pendingLabel?: string;
    pending?: boolean;
    destructive?: boolean;
};

export default function ConfirmActionDialog({
    open,
    onOpenChange,
    onConfirm,
    title,
    description,
    content,
    cancelLabel = 'Cancelar',
    confirmLabel,
    pendingLabel = 'Procesando...',
    pending = false,
    destructive = false,
}: Props) {
    return (
        <Dialog
            open={open}
            onOpenChange={(nextOpen) => {
                if (!pending) {
                    onOpenChange(nextOpen);
                }
            }}
        >
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{title}</DialogTitle>
                    <DialogDescription>{description}</DialogDescription>
                </DialogHeader>
                {content}
                <DialogFooter>
                    <DialogClose asChild>
                        <Button
                            type="button"
                            variant="secondary"
                            disabled={pending}
                        >
                            {cancelLabel}
                        </Button>
                    </DialogClose>
                    <Button
                        type="button"
                        variant={destructive ? 'destructive' : 'default'}
                        disabled={pending}
                        onClick={onConfirm}
                    >
                        {pending ? pendingLabel : confirmLabel}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
