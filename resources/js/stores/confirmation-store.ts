import { create } from 'zustand';

interface ConfirmationState {
  open: boolean;
  title: string;
  description?: string;
  cancelLabel?: string;
  actionLabel?: string;
  actionVariant?: 'default' | 'destructive';
  closeOnAction?: boolean;
  isProcessing?: boolean;
  onAction: (onFinish?: () => void) => void;
  onCancel?: () => void;
}

interface ConfirmationActions {
  openConfirmation: (data: Omit<ConfirmationState, 'open' | 'isProcessing'>) => void;
  closeConfirmation: () => void;
  setProccessing: (isProcessing: boolean) => void;
}

const initialState: ConfirmationState = {
  open: false,
  title: '',
  actionVariant: 'destructive',
  closeOnAction: true,
  isProcessing: false,
  onAction: () => {},
  onCancel: () => {},
};

const useConfirmationStore = create<ConfirmationState & ConfirmationActions>((set) => ({
  ...initialState,
  openConfirmation: ({
    title,
    description,
    cancelLabel,
    actionLabel,
    actionVariant = initialState.actionVariant,
    closeOnAction = initialState.closeOnAction,
    onAction,
    onCancel,
  }) =>
    set((state) => ({
      open: true,
      title,
      description,
      cancelLabel,
      actionLabel,
      actionVariant,
      closeOnAction,
      isProcessing: false,
      onAction: () => {
        state.setProccessing(true);
        onAction(() => {
          state.closeConfirmation();
          state.setProccessing(false);
        });

        if (closeOnAction) {
          state.closeConfirmation();
        }
      },
      onCancel,
    })),
  closeConfirmation: () => set((state) => ({ ...initialState, isProcessing: state.isProcessing })),
  setProccessing: (isProcessing) => set(() => ({ isProcessing })),
}));

export default useConfirmationStore;
