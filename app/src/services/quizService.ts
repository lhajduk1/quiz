import type { ApiResponse, QuizAttempt, QuizQuestion, SubmittedAnswer } from "@/types/quiz";
import { api } from "./api";

export async function fetchQuestions(signal?: AbortSignal): Promise<QuizQuestion[]> {

    const response = await api.get<ApiResponse<QuizQuestion[]>>(
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

export async function submitQuiz(id: number, answers: SubmittedAnswer[]): Promise<QuizAttempt> {
    const response = await api.post<ApiResponse<QuizAttempt>>(
        `/quiz/attempts/${id}/submit`,
        {
            answers
        }
    );

    return response.data.data;
}

export async function createQuizAttempt(signal?: AbortSignal): Promise<QuizAttempt> {
    const response = await api.post<ApiResponse<QuizAttempt>>(
        '/quiz/attempts',
        {},
        { signal }
    );

    return response.data.data;
}

export async function getQuizAttempt(
    id: number,
    signal?: AbortSignal
): Promise<QuizAttempt> {
    const response = await api.get<ApiResponse<QuizAttempt>>(
        `/quiz/attempts/${id}`,
        { signal }
    );

    return response.data.data;
}