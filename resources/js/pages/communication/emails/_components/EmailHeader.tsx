import { PageTitle } from '@/components/PageTitle';
import { useTranslations } from '@/hooks/use-translations';

export function EmailHeader({ name }: { name: string }) {
  const { t } = useTranslations();
  return (
    <header className="my-2 flex flex-col items-center gap-2">
      <PageTitle description={t('Select the :name you want to send a message to', { name })}>{t('Send email to :name', { name })}</PageTitle>
      <small className="text-muted-foreground text-xs">{t('Only :name with an email address will be shown in the list.', { name })}</small>
    </header>
  );
}
