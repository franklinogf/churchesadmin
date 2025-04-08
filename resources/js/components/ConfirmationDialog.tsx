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
import { Fragment } from 'react';
import { buttonVariants } from './ui/button';

const ConfirmationDialog = () => {
    const { open, title, description, cancelLabel, actionLabel, onAction, closeConfirmation, actionVariant } = useConfirmationStore();

    return (
        <AlertDialog open={open} onOpenChange={closeConfirmation}>
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>{title}</AlertDialogTitle>
                    <AlertDialogDescription>
                        {description
                            ? description.split(/\r?\n/).map((line, index) => (
                                  <Fragment key={index}>
                                      {line}
                                      {index < description.split(/\r?\n/).length - 1 && <br />}
                                  </Fragment>
                              ))
                            : null}
                    </AlertDialogDescription>
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
