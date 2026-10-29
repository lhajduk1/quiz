import type { Question } from "@/types/quiz";

export const questions: Array<Question> = [
    {
        id: 1,
        question: 'What does PHP stand for?',
        correctAnswerId: 1,
        answers: [
            { id: 1, text: 'PHP: Hypertext Preprocessor' },
            { id: 2, text: 'Personal Home Page' },
            { id: 3, text: 'Private Hosting Platform' }
        ]
    },
    {
        id: 2,
        question: 'Which symbol is used to declare a variable in PHP?',
        correctAnswerId: 5,
        answers: [
            { id: 4, text: '@' },
            { id: 5, text: '$' },
            { id: 6, text: '#' }
        ]
    },
    {
        id: 3,
        question: 'Which function returns the length of a string in PHP?',
        correctAnswerId: 8,
        answers: [
            { id: 7, text: 'count()' },
            { id: 8, text: 'strlen()' },
            { id: 9, text: 'size()' }
        ]
    },
];

export async function fetchQuestions(): Promise<Question[]> {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            resolve(questions);
            // reject(new Error("Simulated API error"));
        }, 1500);
    });
}

type Post = {
    userId: number;
    id: number;
    title: string;
    body: string;
}

export async function fetchPosts(): Promise<Post[]> {
    const response = await fetch("https://jsonplaceholder.typicode.com/posts");

    if (!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }

    const data = await response.json();

    return data;
}