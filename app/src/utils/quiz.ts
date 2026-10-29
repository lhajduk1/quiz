import { SubmittedAnswer } from "@/types/quiz";

export function prepareAnswers(selectedAnswers: Record<number, number>): SubmittedAnswer[] {
    return Object.entries(selectedAnswers).map(([questionId, answerId]) => {
        return {
            questionId: Number(questionId),
            answerId: answerId
        }
    });
}