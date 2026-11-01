import { QuizContext } from "@/contexts/quizContext";
import { QuizAttempt, QuizQuestion } from "@/types/quiz";
import { useState } from "react";

export default function QuizProvider({ children }: { children: React.ReactNode }) {
    const [quizResult, setQuizResult] = useState<QuizAttempt | null>(null);
    const [questions, setQuestions] = useState<QuizQuestion[]>([]);

    return (
        <QuizContext value={{ quizResult, setQuizResult, questions, setQuestions }}>
            {children}
        </QuizContext>
    )
}