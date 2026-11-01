export type ApiResponse<T> = {
    data: T
}

export type ApiErrorResponse = {
    message: string;
    errors?: Record<string, string[]>;
}

export type Answer = {
    id: number;
    text: string;
}

export type QuizQuestion = {
    id: number,
    question: string,
    answers: Answer[]
}

export type SubmittedAnswer = {
    questionId: number;
    answerId: number;
};

export type QuizResult = {
    correctAnswersCount: number;
    questionsCount: number;
    questions: {
        id: number;
        answeredCorrectly: boolean;
        selectedAnswerId: number;
        correctAnswerId: number;
    }[]
}

export type QuizAttemptQuestion = {
    questionId: number;
    questionSnapshot: string;
    answersSnapshot: Answer[];
    selectedAnswerId?: number;
    correctAnswerId?: number;
    answeredCorrectly?: boolean;
}

export type QuizAttempt = {
    id: number;
    status: "started" | "finished";
    correctAnswersCount: number | null;
    questionsCount: number;
    startedAt: string;
    finishedAt: string | null;
    quizAttemptQuestions: QuizAttemptQuestion[];
}



