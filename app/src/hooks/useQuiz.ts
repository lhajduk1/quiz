import { useState, useEffect } from "react";
import type { ApiErrorResponse, QuizAttempt, QuizQuestion, QuizResult } from "@/types/quiz";
import { createQuizAttempt, fetchQuestions, submitQuiz } from "@/services/quizService";
import { prepareAnswers } from "@/utils/quiz";
import axios from "axios";
import { useQuizContext } from "./useQuizContext";

export function useQuiz() {
    const [quizAttemptId, setQuizAttemptId] = useState<number | null>(null);
    const { quizResult, setQuizResult, questions, setQuestions } = useQuizContext();
    const [selectedAnswers, setSelectedAnswers] = useState<Record<number, number>>({})
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState<number>(0);
    const currentQuestion = questions[currentQuestionIndex];
    const isLastQuestion = currentQuestionIndex === questions.length - 1;
    const hasPreviousQuestion = currentQuestionIndex > 0;
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [loadingError, setLoadingError] = useState<string | null>(null);
    const [submitError, setSubmitError] = useState<string | null>(null);
    const [isSubmitting, setIsSubmitting] = useState<boolean>(false);

    function selectAnswer(questionId: number, answerId: number): void {
        setSelectedAnswers((previous) => ({
            ...previous,
            [questionId]: answerId,
        }));
    }

    function nextQuestion(): void {
        setCurrentQuestionIndex((previous) => {
            const lastIndex = Math.max(0, questions.length - 1);

            return Math.min(previous + 1, lastIndex);
        });
    }

    function previousQuestion(): void {
        setCurrentQuestionIndex((previous) => Math.max(previous - 1, 0));
    }

    async function loadQuestions(signal?: AbortSignal): Promise<void> {
        setIsLoading(true);
        setLoadingError(null);

        try {
            const data = await createQuizAttempt(signal);

            if (signal?.aborted) return;

            setQuizAttemptId(data.id);

            const questions = data.quizAttemptQuestions.map(question => ({
                id: question.questionId,
                question: question.questionSnapshot,
                answers: question.answersSnapshot
            }));

            setQuestions(questions);
        } catch (error) {
            if (axios.isCancel(error) || signal?.aborted) {
                return;
            }

            if (axios.isAxiosError(error)) {
                setLoadingError(
                    error.response
                        ? `HTTP error: ${error.response.status}`
                        : error.message
                );
            } else {
                setLoadingError('An unexpected error occurred.');
            }
        } finally {
            if (!signal?.aborted) {
                setIsLoading(false);
            }
        }
    }

    async function handleSubmitQuiz(): Promise<boolean> {
        if (quizAttemptId === null) {
            setSubmitError('quizAttemptId cannot be null');
            return false;
        }

        setIsSubmitting(true);
        setSubmitError(null);

        try {

            const data = await submitQuiz(quizAttemptId, prepareAnswers(selectedAnswers));

            setQuizResult(data);

            return true;
        } catch (error) {
            if (axios.isCancel(error)) {
                return false;
            }

            if (axios.isAxiosError<ApiErrorResponse>(error)) {
                const status = error.response?.status;

                if (!error.response) {
                    setSubmitError('Unable to connect to the server.');
                } else if (status && status >= 500) {
                    setSubmitError('Server error. Please try again later.');
                } else {
                    setSubmitError(
                        error.response.data?.message ?? 'Unable to submit quiz.'
                    );
                }
            } else {
                setSubmitError('An unexpected error occurred.');
            }

            return false;
        } finally {
            setIsSubmitting(false);
        }
    }

    useEffect(() => {
        const controller = new AbortController();

        loadQuestions(controller.signal);

        return () => {
            controller.abort();
        }
    }, []);

    return {
        questions,
        selectedAnswers,
        currentQuestionIndex,
        currentQuestion,
        isLastQuestion,
        hasPreviousQuestion,
        isLoading,
        loadingError,
        submitError,
        quizResult,
        isSubmitting,
        selectAnswer,
        nextQuestion,
        previousQuestion,
        loadQuestions,
        handleSubmitQuiz
    };
}