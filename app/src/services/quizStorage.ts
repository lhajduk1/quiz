import type { SavedQuizProgress } from "@/types/quiz"
import AsyncStorage from '@react-native-async-storage/async-storage';

const storageKey = 'quiz_progress';
let saveQueue: Promise<void> = Promise.resolve();

export function saveQuizProgress(progress: SavedQuizProgress): Promise<void> {
    const value = JSON.stringify(progress);

    return AsyncStorage.setItem(storageKey, value);
}

export async function loadQuizProgress(): Promise<SavedQuizProgress | null> {
    const value = await AsyncStorage.getItem(storageKey);

    if (value === null) {
        return null;
    }

    try {
        const result: unknown = JSON.parse(value);

        if (isSavedQuizProgress(result)) {
            return result;
        }
    } catch (error) {
        console.error('Failed to parse saved quiz progress:', error);
        return null;
    }

    return null;
}

export async function clearQuizProgress(): Promise<void> {
    await AsyncStorage.removeItem(storageKey);
}

export function saveQuizProgressQueued(progress: SavedQuizProgress): Promise<void> {
    const next = saveQueue
        .catch(() => { })
        .then(() => saveQuizProgress(progress));

    saveQueue = next;

    return next;
}

export function clearQuizProgressQueued(): Promise<void> {
    const next = saveQueue
        .catch(() => { })
        .then(() => clearQuizProgress());

    saveQueue = next;

    return next;
}

function isSavedQuizProgress(value: unknown): value is SavedQuizProgress {
    if (typeof value !== "object" || value === null) {
        return false;
    }

    if (!('quizAttempt' in value)) {
        return false;
    }

    if (typeof value.quizAttempt !== 'object' || value.quizAttempt === null) {
        return false;
    }

    if (!('id' in value.quizAttempt)) {
        return false;
    }

    if (typeof value.quizAttempt.id !== 'number' || !Number.isInteger(value.quizAttempt.id)) {
        return false;
    }

    if (!('status' in value.quizAttempt)) {
        return false;
    }

    if (value.quizAttempt.status !== "finished" && value.quizAttempt.status !== 'started') {
        return false;
    }

    if (!('questionsCount' in value.quizAttempt)) {
        return false;
    }

    if (typeof value.quizAttempt.questionsCount !== 'number' || !Number.isInteger(value.quizAttempt.questionsCount) || value.quizAttempt.questionsCount <= 0) {
        return false;
    }

    if (!('correctAnswersCount' in value.quizAttempt)) {
        return false;
    }

    if (value.quizAttempt.correctAnswersCount !== null) {
        if (
            typeof value.quizAttempt.correctAnswersCount !== 'number' ||
            !Number.isInteger(value.quizAttempt.correctAnswersCount) ||
            value.quizAttempt.correctAnswersCount < 0 ||
            value.quizAttempt.correctAnswersCount > value.quizAttempt.questionsCount
        ) {
            return false;
        }
    }

    if (!('startedAt' in value.quizAttempt)) {
        return false;
    }

    if (typeof value.quizAttempt.startedAt !== 'string') {
        return false;
    }

    if (!('finishedAt' in value.quizAttempt)) {
        return false;
    }

    if (value.quizAttempt.finishedAt !== null && typeof value.quizAttempt.finishedAt !== 'string') {
        return false;
    }

    if (!('quizAttemptQuestions' in value.quizAttempt)) {
        return false;
    }

    if (!Array.isArray(value.quizAttempt.quizAttemptQuestions)) {
        return false;
    }

    if (value.quizAttempt.quizAttemptQuestions.length !== value.quizAttempt.questionsCount) {
        return false;
    }

    if (!('selectedAnswers' in value)) {
        return false;
    }

    if (Array.isArray(value.selectedAnswers) || typeof value.selectedAnswers !== 'object' || value.selectedAnswers === null) {
        return false;
    }

    if (!value.quizAttempt.quizAttemptQuestions.every((currentValue: unknown) => {
        if (typeof currentValue !== 'object' || currentValue === null) {
            return false;
        }

        if (!('questionId' in currentValue)) {
            return false;
        }

        if (typeof currentValue.questionId !== 'number' || !Number.isInteger(currentValue.questionId)) {
            return false;
        }

        if (!('questionSnapshot' in currentValue)) {
            return false;
        }

        if (typeof currentValue.questionSnapshot !== 'string') {
            return false;
        }

        if (!('answersSnapshot' in currentValue)) {
            return false;
        }

        if (!Array.isArray(currentValue.answersSnapshot)) {
            return false;
        }

        if (!currentValue.answersSnapshot.every((answer: unknown) => {
            if (typeof answer !== 'object' || answer === null) {
                return false;
            }

            if (!('id' in answer)) {
                return false;
            }

            if (typeof answer.id !== 'number' || !Number.isInteger(answer.id)) {
                return false;
            }

            if (!('text' in answer)) {
                return false;
            }

            if (typeof answer.text !== 'string') {
                return false;
            }

            return true;
        })) {
            return false;
        }

        if ('selectedAnswerId' in currentValue) {
            if (typeof currentValue.selectedAnswerId !== 'number' || !Number.isInteger(currentValue.selectedAnswerId)) {
                return false;
            }
        }

        if ('correctAnswerId' in currentValue) {
            if (typeof currentValue.correctAnswerId !== 'number' || !Number.isInteger(currentValue.correctAnswerId)) {
                return false;
            }
        }

        if ('answeredCorrectly' in currentValue) {
            if (typeof currentValue.answeredCorrectly !== 'boolean') {
                return false;
            }
        }

        return true;
    })) {
        return false;
    }

    if (!Object.values(value.selectedAnswers).every((currentValue) => Number.isInteger(currentValue))) {
        return false;
    }

    if (!('currentQuestionIndex' in value)) {
        return false;
    }

    if (typeof value.currentQuestionIndex !== 'number' || !Number.isInteger(value.currentQuestionIndex) || value.currentQuestionIndex < 0 || value.currentQuestionIndex >= value.quizAttempt.questionsCount) {
        return false;
    }

    return true;
}