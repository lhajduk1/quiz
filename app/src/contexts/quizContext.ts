import type { QuizAttempt, QuizQuestion, QuizResult } from "@/types/quiz";
import { createContext, type Dispatch, type SetStateAction } from "react";

export type QuizContextType = {
    quizResult: QuizAttempt | null;
    questions: QuizQuestion[];
    setQuizResult: Dispatch<SetStateAction<QuizAttempt | null>>;
    setQuestions: Dispatch<SetStateAction<QuizQuestion[]>>;
}

export const QuizContext = createContext<QuizContextType | undefined>(undefined);