import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { SkillForm } from '@/components/forms/skill-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { UserPermission } from '@/enums/user';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import { type Tag } from '@/types/models/tag';
import { useState } from 'react';
import { columns } from './includes/columns';

interface IndexPageProps {
  skills: Tag[];
}
export default function Index({ skills }: IndexPageProps) {
  const { t } = useTranslations<string>();
  const { can: userCan } = useUser();
  const [open, setOpen] = useState(false);

  return (
    <AppLayout breadcrumbs={[{ title: t('Skills') }]} title={t('Skills')}>
      <PageTitle>{t('Skills')}</PageTitle>
      <div className="mx-auto w-full max-w-3xl">
        <SkillForm open={open} setOpen={setOpen} />
        <DataTable
          headerButton={
            userCan(UserPermission.SKILLS_CREATE) && (
              <Button size="sm" onClick={() => setOpen(true)}>
                {t('Add :model', { model: t('skill') })}
              </Button>
            )
          }
          columns={columns}
          data={skills}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
