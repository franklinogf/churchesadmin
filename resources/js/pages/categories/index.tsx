import { DataTable } from '@/components/custom-ui/datatable/data-table';
import { CategoryForm } from '@/components/forms/category-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { UserPermission } from '@/enums/user';
import { useUser } from '@/hooks/use-permissions';
import AppLayout from '@/layouts/app-layout';
import { type Tag } from '@/types/models/tag';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { useState } from 'react';
import { columns } from './includes/columns';

interface IndexPageProps {
  categories: Tag[];
}
export default function Index({ categories }: IndexPageProps) {
  const { t } = useLaravelReactI18n();
  const { can: userCan } = useUser();
  const [open, setOpen] = useState(false);
  return (
    <AppLayout breadcrumbs={[{ title: t('Categories') }]} title={t('Categories')}>
      <PageTitle>{t('Categories')}</PageTitle>
      <div className="mx-auto w-full max-w-3xl">
        <CategoryForm open={open} setOpen={setOpen} />
        <DataTable
          headerButton={
            userCan(UserPermission.CREATE_CATEGORIES) && (
              <Button size="sm" onClick={() => setOpen(true)}>
                {t('Add Category')}
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
