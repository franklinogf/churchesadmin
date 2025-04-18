import type { Wallet } from '@/types/models/wallet';

export default function Show({ wallet }: { wallet: Wallet }) {
  //   const { t } = useLaravelReactI18n();
  return (
    <div>
      <h1>Wallet Details</h1>
      <pre>{JSON.stringify(wallet, null, 2)}</pre>
    </div>
  );
}
