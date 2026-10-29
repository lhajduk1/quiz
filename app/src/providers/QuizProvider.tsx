import { QuizContext } from "@/contexts/quizContext";
import { QuizQuestion, QuizResult } from "@/types/quiz";
import { useState } from "react";

export default function QuizProvider({ children }: { children: React.ReactNode }) {
    const [quizResult, setQuizResult] = useState<QuizResult | null>(null);
    const [questions, setQuestions] = useState<QuizQuestion[]>([]);

    return (
        <QuizContext value={{ quizResult, setQuizResult, questions, setQuestions }}>
            {children}
        </QuizContext>
    )
}