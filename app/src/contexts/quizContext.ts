import type { QuizQuestion, QuizResult } from "@/types/quiz";
import { createContext, type Dispatch, type SetStateAction } from "react";

export type QuizContextType = {
    quizResult: QuizResult | null;
    questions: QuizQuestion[];
    setQuizResult: Dispatch<SetStateAction<QuizResult | null>>;
    setQuestions: Dispatch<SetStateAction<QuizQuestion[]>>;
}

export const QuizContext = createContext<QuizContextType | undefined>(undefined);