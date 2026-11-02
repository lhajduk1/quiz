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

    return value !== null ? JSON.parse(value) : null;
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