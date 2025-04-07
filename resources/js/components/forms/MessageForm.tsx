import { FileField } from '@/components/forms/inputs/FileField';
import { InputField } from '@/components/forms/inputs/InputField';
import { RichTextField } from '@/components/forms/inputs/RichTextField';
import SubmitButton from '@/components/forms/SubmitButton';
import { Card, CardContent } from '@/components/ui/card';
import { useTranslations } from '@/hooks/translations';
import { InertiaHTTPMethod } from '@/types';
import { useForm } from '@inertiajs/react';

export function MessageForm({
    onSubmit,
    to,
    extras,
    isReplying = false,
}: {
    extras?: { [key: string]: any };
    to?: string;
    isReplying?: boolean;
    onSubmit: (post: InertiaHTTPMethod) => void;
}) {
    const { data, setData, errors, processing, post } = useForm<{
        subject: string;
        message: string;
        files: string[];
        [key: string]: any;
    }>({
        subject: '',
        message: '',
        files: [],
        ...extras,
    });

    const { t } = useTranslations();
    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        onSubmit(post);
    }
    return (
        <form onSubmit={handleSubmit}>
            <Card>
                <CardContent className="space-y-2">
                    {!isReplying && (
                        <>
                            {to && <InputField label={t('Para')} name="to" disabled defaultValue={to} />}
                            <InputField
                                required
                                value={data.subject}
                                onChange={(value) => {
                                    setData('subject', value);
                                }}
                                error={errors.subject}
                                label={t('Asunto')}
                                name="subject"
                            />
                        </>
                    )}
                    <RichTextField
                        label={t('Mensaje')}
                        value={data.message}
                        onChange={(value) => {
                            setData('message', value);
                        }}
                        error={errors.message}
                    />
                    <FileField
                        label={t('Adjuntos')}
                        allowMultiple
                        onChange={(values) => {
                            setData('files', values);
                        }}
                    />
                </CardContent>
            </Card>
            <div className="mt-2 flex justify-center">
                <SubmitButton isSubmitting={processing}>{t('Enviar')}</SubmitButton>
            </div>
        </form>
    );
}
