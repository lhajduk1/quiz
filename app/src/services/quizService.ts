import type { QuizQuestion, QuizResult, SubmittedAnswer } from "@/types/quiz";
import { api } from "./api";

export async function fetchQuestions(signal?: AbortSignal): Promise<QuizQuestion[]> {

    const response = await api.get<{ data: QuizQuestion[] }>(
        '/questions',
        {
            signal,
            params: {
                limit: 10
            }
        }
    )

    return response.data.data;
}

export async function submitQuiz(answers: SubmittedAnswer[]): Promise<QuizResult> {
    const response = await api.post<{ data: QuizResult }>(
        '/quiz/submit',
        { answers }
    );

    return response.data.data;
}