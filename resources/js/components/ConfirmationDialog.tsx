import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import useConfirmationStore from '@/stores/confirmationStore';
import { buttonVariants } from './ui/button';

const ConfirmationDialog = () => {
    const { open, title, description, cancelLabel, actionLabel, onAction, closeConfirmation, actionVariant } = useConfirmationStore();

    return (
        <AlertDialog open={open} onOpenChange={closeConfirmation}>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>{title}</AlertDialogTitle>
                    <AlertDialogDescription>{description ?? null}</AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>{cancelLabel}</AlertDialogCancel>
                    <AlertDialogAction className={buttonVariants({ variant: actionVariant })} onClick={onAction}>
                        {actionLabel}
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
};

export default ConfirmationDialog;
