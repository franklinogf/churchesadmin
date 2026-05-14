import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import type { Email } from '@/types/models/email';
import { useTranslation } from 'react-i18next';

interface ErrorMessageDialogProps {
  email: Email;
  open: boolean;
  setOpen: (open: boolean) => void;
}
export function ErrorMessageDialog({ email, open, setOpen }: ErrorMessageDialogProps) {
  const { t: tPages } = useTranslation('pages');
  return (
    <Dialog open={open} onOpenChange={setOpen}>
      <DialogContent className="sm:max-w-150">
        <DialogHeader>
          <DialogTitle>{tPages(($) => $.communication.emails.components.errorMessageDialog.errorMessage)}</DialogTitle>
          <DialogDescription>{tPages(($) => $.communication.emails.components.errorMessageDialog.emailErrorIfAny)}</DialogDescription>
        </DialogHeader>

        <ScrollArea className="max-h-100 overflow-hidden">
          <div className="prose dark:prose-invert prose-sky">
            <pre className="max-w-full text-balance">
              {email.errorMessage ?? tPages(($) => $.communication.emails.components.errorMessageDialog.noErrorMessageAvailable)}
            </pre>
          </div>
          <ScrollBar orientation="horizontal" />
        </ScrollArea>

        <DialogFooter>
          <DialogClose asChild>
            <Button variant="outline">{tPages(($) => $.communication.emails.components.errorMessageDialog.close)}</Button>
          </DialogClose>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
