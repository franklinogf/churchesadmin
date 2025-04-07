import { Loader2Icon } from "lucide-react";
import { AnimatePresence, motion, Variants } from "motion/react";

const variants: Variants = {
  visible: { opacity: 1, y: 0 },
  hidden: { opacity: 0, y: -15 },
};
export function LoadingTextSwap({
  isLoading,
  children,
  loadingIcon,
}: {
  isLoading?: boolean;
  children: React.ReactNode;
  loadingIcon?: React.ReactNode;
}) {
  return (
    <div className="relative flex items-center justify-center">
      <AnimatePresence mode="wait" initial={false}>
        {isLoading ? (
          <motion.span
            variants={variants}
            key="loading"
            initial="hidden"
            animate="visible"
            exit="hidden"
            transition={{ duration: 0.3, type: "spring" }}
            className="absolute"
          >
            {loadingIcon || <Loader2Icon className="animate-spin" />}
          </motion.span>
        ) : (
          <motion.span
            variants={variants}
            key="content"
            initial="hidden"
            animate="visible"
            exit="hidden"
            transition={{ duration: 0.3, type: "spring" }}
            className="absolute"
          >
            {children}
          </motion.span>
        )}
      </AnimatePresence>
      <span aria-readonly className="invisible">
        {children}
      </span>
    </div>
  );
}
