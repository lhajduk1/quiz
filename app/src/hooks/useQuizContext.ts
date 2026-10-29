import { QuizContext, QuizContextType } from "@/contexts/quizContext";
import { useContext } from "react";

export function useQuizContext(): QuizContextType {
    const context = useContext(QuizContext);

    if (context === undefined) {
        throw new Error('useQuizContext must be used within QuizProvider');
    }

    return context;
}