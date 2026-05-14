import { PageTitle } from '@/components/PageTitle';
import { useTranslation } from 'react-i18next';

export function EmailHeader({ name }: { name: string }) {
  const { t: tPages } = useTranslation('pages');
  return (
    <header className="my-2 flex flex-col items-center gap-2">
      <PageTitle description={tPages(($) => $.communication.emails.components.EmailHeader.selectTheNameYouWantToSendAMessage, { name })}>
        {tPages(($) => $.communication.emails.components.EmailHeader.sendEmailToName, { name })}
      </PageTitle>
      <small className="text-muted-foreground text-xs">
        {tPages(($) => $.communication.emails.components.EmailHeader.onlyNameWithAnEmailAddressWillBeShown, { name })}
      </small>
    </header>
  );
}
