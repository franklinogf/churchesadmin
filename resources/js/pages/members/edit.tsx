import { AddressFormSkeleton } from '@/components/forms/AddressFormSkeleton';
import { Form } from '@/components/forms/Form';
import { DatetimeField } from '@/components/forms/inputs/DatetimeField';
import { FieldsGrid } from '@/components/forms/inputs/FieldsGrid';
import { InputField } from '@/components/forms/inputs/InputField';
import { MultiSelectField } from '@/components/forms/inputs/MultiSelectField';
import { PhoneField } from '@/components/forms/inputs/PhoneField';
import { SelectField } from '@/components/forms/inputs/SelectField';
import { PageTitle } from '@/components/PageTitle';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Separator } from '@/components/ui/separator';
import { TenantPermission } from '@/enums/TenantPermission';
import { useTranslations } from '@/hooks/use-translations';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import { convertTagsToMultiselectOptions, getMultiselecOptionsLabels } from '@/lib/mutliselect';
import useConfirmationStore from '@/stores/confirmationStore';
import type { BreadcrumbItem, SelectOption } from '@/types';
import { type AddressFormData } from '@/types/models/address';
import { type DeactivationCode } from '@/types/models/deactivation-code';
import { type Member, type MemberFormData } from '@/types/models/member';
import { type Tag } from '@/types/models/tag';
import { router, useForm } from '@inertiajs/react';
import { CheckCircleIcon, XCircleIcon } from 'lucide-react';
import { useState } from 'react';

type EditForm = MemberFormData & {
  address: AddressFormData;
};

interface EditPageProps {
  genders: SelectOption[];
  civilStatuses: SelectOption[];
  skills: Tag[];
  categories: Tag[];
  member: Member;
  deactivationCodes: DeactivationCode[];
}
export default function Edit({ member, genders, civilStatuses, skills, categories, deactivationCodes }: EditPageProps) {
  const { t } = useTranslations();
  const { can: userCan } = useUser();
  const { openConfirmation } = useConfirmationStore();
  const [deactivateDialogOpen, setDeactivateDialogOpen] = useState(false);
  const [selectedDeactivationCode, setSelectedDeactivationCode] = useState<string>('');
  const { data, setData, put, errors, processing, transform } = useForm<EditForm>({
    name: member.name,
    last_name: member.lastName,
    email: member.email ?? '',
    phone: member.phone ?? '',
    dob: member.dob ?? '',
    gender: member.gender,
    civil_status: member.civilStatus,
    skills: convertTagsToMultiselectOptions(member.skills),
    categories: convertTagsToMultiselectOptions(member.categories),
    address: {
      address_1: member.address?.address1 ?? '',
      address_2: member.address?.address2 ?? '',
      city: member.address?.city ?? '',
      state: member.address?.state ?? '',
      country: member.address?.country ?? '',
      zip_code: member.address?.zipCode ?? '',
    },
  });

  transform((data) => ({
    ...data,
    skills: getMultiselecOptionsLabels(data.skills),
    categories: getMultiselecOptionsLabels(data.categories),
  }));

  const handleSubmit = () => {
    put(route('members.update', member.id), { preserveScroll: true });
  };
  const breadcrumbs: BreadcrumbItem[] = [
    {
      title: t('Members'),
      href: route('members.index'),
    },
    {
      title: t('Edit :model', { model: t('Member') }),
    },
  ];
  return (
    <AppLayout breadcrumbs={breadcrumbs} title={t('Members')}>
      <PageTitle>{t('Edit :model', { model: t('Member') })}</PageTitle>
      <div className="mx-auto mt-6 grid max-w-7xl grid-cols-1 gap-6 px-4 lg:grid-cols-3">
        {/* Main Form - Takes 2/3 of the space on large screens */}
        <div className="lg:col-span-2">
          <Form isSubmitting={processing} className="w-full" onSubmit={handleSubmit}>
            <InputField required label="Name" value={data.name} onChange={(value) => setData('name', value)} error={errors.name} />
            <InputField
              required
              label="Last Name"
              value={data.last_name}
              onChange={(value) => setData('last_name', value)}
              error={errors.last_name}
            />
            <FieldsGrid>
              <InputField label="Email" type="email" value={data.email} onChange={(value) => setData('email', value)} error={errors.email} />
              <PhoneField label="Phone" value={data.phone} onChange={(value) => setData('phone', value)} error={errors.phone} />
            </FieldsGrid>

            <DatetimeField
              hideTime
              max={new Date()}
              label="Date of Birth"
              value={data.dob}
              onChange={(value) => setData('dob', value)}
              error={errors.dob}
            />

            <FieldsGrid>
              <SelectField
                required
                label="Gender"
                value={data.gender}
                onChange={(value) => setData('gender', value)}
                options={genders}
                error={errors.gender}
              />
              <SelectField
                required
                label="Civil Status"
                value={data.civil_status}
                onChange={(value) => setData('civil_status', value)}
                options={civilStatuses}
                error={errors.civil_status}
              />
            </FieldsGrid>

            <FieldsGrid>
              <MultiSelectField
                label={t('Skills')}
                value={data.skills}
                onChange={(value) => setData('skills', value)}
                options={skills}
                error={errors.skills}
              />
              <MultiSelectField
                label={t('Categories')}
                value={data.categories}
                onChange={(value) => setData('categories', value)}
                options={categories}
                error={errors.categories}
              />
            </FieldsGrid>
            <Separator className="my-8" />

            <AddressFormSkeleton
              data={data.address}
              setData={(value) => {
                setData('address', value);
              }}
              errors={errors}
              errorsName="address"
            />
          </Form>
        </div>

        {/* Member Status Sidebar - Takes 1/3 of the space on large screens */}
        <div className="lg:col-span-1">
          <div className="sticky top-6 space-y-4">
            <Card>
              <CardHeader className="pb-3">
                <CardTitle className="flex items-center gap-3 text-lg">
                  {member.active ? (
                    <>
                      <div className="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                        <CheckCircleIcon className="h-5 w-5 text-green-600" />
                      </div>
                      {t('Status')}
                    </>
                  ) : (
                    <>
                      <div className="flex h-8 w-8 items-center justify-center rounded-full bg-red-100">
                        <XCircleIcon className="h-5 w-5 text-red-600" />
                      </div>
                      {t('Status')}
                    </>
                  )}
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-6">
                <div className="space-y-3">
                  <div className="flex items-center justify-between">
                    <span className="text-muted-foreground text-sm font-medium">{t('Status')}</span>
                    <Badge variant={member.active ? 'default' : 'destructive'} className="text-sm font-semibold">
                      {member.active ? t('Active') : t('Inactive')}
                    </Badge>
                  </div>

                  {!member.active && member.deactivationCode && (
                    <div className="flex items-center justify-between">
                      <span className="text-muted-foreground text-sm font-medium">{t('Deactivation code')}</span>
                      <Badge variant="outline" className="text-sm">
                        {member.deactivationCode.name}
                      </Badge>
                    </div>
                  )}

                  {member.active && (
                    <p className="text-muted-foreground text-sm">
                      {t('This member is currently active and will appear in member lists and reports.')}
                    </p>
                  )}

                  {!member.active && (
                    <p className="text-muted-foreground text-sm">{t('This member is inactive and will not appear in member lists by default.')}</p>
                  )}
                </div>
                <Separator />

                <div className="space-y-3">
                  <div className="space-y-2">
                    {member.active && userCan(TenantPermission.MEMBERS_DEACTIVATE) && (
                      <Dialog open={deactivateDialogOpen} onOpenChange={setDeactivateDialogOpen}>
                        <DialogTrigger asChild>
                          <Button variant="destructive" size="sm" className="w-full justify-start">
                            <XCircleIcon className="mr-2 h-4 w-4" />
                            {t('Deactivate')} {t('Member')}
                          </Button>
                        </DialogTrigger>
                        <DialogContent className="sm:max-w-md">
                          <DialogHeader>
                            <DialogTitle className="flex items-center gap-2">
                              <XCircleIcon className="h-5 w-5 text-red-600" />
                              {t('Deactivate')} {t('Member')}
                            </DialogTitle>
                            <DialogDescription className="pt-2">
                              {t('Are you sure you want to deactivate this :model?', { model: t('Member') })}
                              <br />
                              <span className="text-muted-foreground mt-2 block text-sm">
                                {t('Please select a reason for deactivation. This will remove the member from active lists.')}
                              </span>
                            </DialogDescription>
                          </DialogHeader>
                          <div className="space-y-4 py-4">
                            <SelectField
                              label={t('Deactivation code')}
                              value={selectedDeactivationCode}
                              onChange={setSelectedDeactivationCode}
                              options={deactivationCodes.map((code) => ({ value: code.id.toString(), label: code.name }))}
                              required
                            />
                          </div>
                          <DialogFooter className="gap-2">
                            <Button variant="outline" onClick={() => setDeactivateDialogOpen(false)} className="flex-1">
                              {t('Cancel')}
                            </Button>
                            <Button
                              variant="destructive"
                              onClick={() => {
                                if (selectedDeactivationCode) {
                                  router.patch(
                                    route('members.deactivate', member.id),
                                    { deactivation_code_id: selectedDeactivationCode },
                                    {
                                      preserveState: true,
                                      preserveScroll: true,
                                      onSuccess: () => {
                                        setDeactivateDialogOpen(false);
                                        setSelectedDeactivationCode('');
                                      },
                                    },
                                  );
                                }
                              }}
                              disabled={!selectedDeactivationCode}
                              className="flex-1"
                            >
                              <XCircleIcon className="mr-2 h-4 w-4" />
                              {t('Deactivate')}
                            </Button>
                          </DialogFooter>
                        </DialogContent>
                      </Dialog>
                    )}

                    {!member.active && userCan(TenantPermission.MEMBERS_ACTIVATE) && (
                      <Button
                        variant="default"
                        size="sm"
                        className="w-full justify-start"
                        onClick={() => {
                          openConfirmation({
                            title: t('Activate'),
                            description: t('Are you sure you want to activate this :model?', { model: t('Member') }),
                            actionLabel: t('Activate'),
                            actionVariant: 'default',
                            cancelLabel: t('Cancel'),
                            onAction: () => {
                              router.patch(
                                route('members.activate', member.id),
                                {},
                                {
                                  preserveState: true,
                                  preserveScroll: true,
                                },
                              );
                            },
                          });
                        }}
                      >
                        <CheckCircleIcon className="mr-2 h-4 w-4" />
                        {t('Activate')} {t('Member')}
                      </Button>
                    )}
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* Quick Info Card */}
            <Card className="border-muted bg-muted/20">
              <CardContent className="p-4">
                <h4 className="mb-2 flex items-center gap-2 text-sm font-semibold">
                  <span className="h-2 w-2 rounded-full bg-blue-500"></span>
                  {t('Quick Info')}
                </h4>
                <div className="text-muted-foreground space-y-2 text-xs">
                  <p>• {t('Active members appear in member lists and reports')}</p>
                  <p>• {t('Inactive members are hidden from default views')}</p>
                  <p>• {t('Deactivation reasons help track why members left')}</p>
                  <p>• {t('Members can be reactivated at any time')}</p>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
