// confirmationStore.ts
import { create } from 'zustand';

interface ConfirmationState {
  open: boolean;
  title: string | null;
  description?: string;
  cancelLabel: string | null;
  actionLabel: string | null;
  actionVariant?: 'default' | 'destructive';
  onAction: () => void;
  onCancel?: () => void;
}

interface ConfirmationActions {
  openConfirmation: (data: Omit<ConfirmationState, 'open'>) => void;
  closeConfirmation: () => void;
}
const initialState: ConfirmationState = {
  open: false,
  title: null,
  description: undefined,
  cancelLabel: null,
  actionLabel: null,
  actionVariant: 'default',
  onAction: () => {},
  onCancel: () => {},
};

const useConfirmationStore = create<ConfirmationState & ConfirmationActions>((set) => ({
  ...initialState,
  openConfirmation: (data) =>
    set(() => ({
      open: true,
      title: data.title,
      description: data.description,
      cancelLabel: data.cancelLabel ?? initialState.cancelLabel,
      actionLabel: data.actionLabel,
      actionVariant: data.actionVariant ?? initialState.actionVariant,
      onAction: data.onAction,
      onCancel: data.onCancel,
    })),
  closeConfirmation: () => set(() => ({ ...initialState })),
}));

export default useConfirmationStore;
