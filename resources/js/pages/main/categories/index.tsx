import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { CategoryForm } from '@/components/forms/category-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import { type Tag } from '@/types/models/tag';
import { useState } from 'react';
import { columns } from './includes/columns';

interface IndexPageProps {
  categories: Tag[];
}
export default function Index({ categories }: IndexPageProps) {
  const { t } = useTranslations();
  const { can: userCan } = useUser();
  const [open, setOpen] = useState(false);
  return (
    <AppLayout breadcrumbs={[{ title: t('Categories') }]} title={t('Categories')}>
      <PageTitle>{t('Categories')}</PageTitle>
      <div className="mx-auto w-full max-w-3xl">
        <CategoryForm open={open} setOpen={setOpen} />
        <DataTable
          headerButton={
            userCan(TenantPermission.CATEGORIES_CREATE) && (
              <Button size="sm" onClick={() => setOpen(true)}>
                {t('Add :model', { model: t('Category') })}
              </Button>
            )
          }
          columns={columns}
          data={categories}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
