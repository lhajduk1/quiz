import QuestionReview from "@/components/quiz/QuestionReview";
import { getQuizAttempt } from "@/services/quizService";
import { QuizAttempt } from "@/types/quiz";
import axios from "axios";
import { useLocalSearchParams } from "expo-router";
import { useEffect, useState } from "react";
import { ActivityIndicator, FlatList, StyleSheet, Text, View } from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";


export default function HistoryDetails() {
    const { id } = useLocalSearchParams<{ id: string }>();
    const [attempt, setAttempt] = useState<QuizAttempt | null>(null);
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);

    const attemptId = Number(id);
    const isValidId = Number.isSafeInteger(attemptId) && attemptId > 0;

    async function loadQuizAttempt(signal: AbortSignal): Promise<void> {
        setIsLoading(true);
        setError(null);
        setAttempt(null);

        try {
            const data = await getQuizAttempt(attemptId, signal);

            if (signal.aborted) return;

            setAttempt(data);
        } catch (error) {
            if (signal.aborted || axios.isCancel(error)) {
                return;
            }

            if (axios.isAxiosError(error)) {
                setError(
                    error.response
                        ? `HTTP error: ${error.response.status}`
                        : error.message
                );
            } else {
                setError('An unexpected error occurred while get attempts history.');
            }
        } finally {
            if (!signal.aborted) {
                setIsLoading(false);
            }
        }
    }

    useEffect(() => {
        const controller = new AbortController();

        if (isValidId) {
            loadQuizAttempt(controller.signal);
        }

        return () => {
            controller.abort();
        }
    }, [attemptId, isValidId]);

    if (!isValidId) {
        return (
            <SafeAreaView>
                <Text>Invalid attempt identifier.</Text>
            </SafeAreaView>
        );
    }

    if (isLoading) {
        return (
            <SafeAreaView style={styles.center}>
                <ActivityIndicator size="large" color="#6366F1" />
            </SafeAreaView>
        );
    }

    if (error !== null) {
        return (
            <SafeAreaView style={styles.center}>
                <Text style={styles.errorText}>{error}</Text>
            </SafeAreaView>
        );
    }

    if (attempt === null) {
        return (
            <SafeAreaView>
                <Text>No attempt was found.</Text>
            </SafeAreaView>
        );
    }

    return (
        <SafeAreaView style={styles.container}>
            <FlatList
                data={attempt.quizAttemptQuestions}
                contentContainerStyle={styles.content}
                keyExtractor={(item) => item.questionId.toString()}
                renderItem={({ item, index }) => (
                    <QuestionReview
                        question={item}
                        index={index}
                    />
                )}
                ListHeaderComponent={() => {
                    const percentage = attempt.questionsCount > 0
                        ? Math.round(
                            ((attempt.correctAnswersCount ?? 0) / attempt.questionsCount) * 100
                        )
                        : 0;

                    return (
                        <View>
                            <View style={styles.cardRow}>
                                <View>
                                    <Text style={styles.cardTitle}>
                                        Attempt #{attempt.id}
                                    </Text>
                                    <Text style={styles.date}>
                                        {attempt.finishedAt
                                            ? new Date(attempt.finishedAt).toLocaleDateString('pl-PL')
                                            : 'Not finished'}
                                    </Text>
                                </View>

                                <View style={styles.scoreContainer}>
                                    <Text style={styles.score}>
                                        {attempt.correctAnswersCount}/{attempt.questionsCount}
                                    </Text>
                                    <Text style={styles.percentage}>
                                        {percentage}% correct
                                    </Text>
                                </View>
                            </View>

                            <View style={styles.progressTrack}>
                                <View
                                    style={[
                                        styles.progressFill,
                                        {
                                            width: `${percentage}%`,
                                            backgroundColor:
                                                percentage >= 70 ? '#34D399' : '#FBBF24',
                                        },
                                    ]}
                                />
                            </View>
                        </View>
                    )
                }}
            />
        </SafeAreaView>
    )
}

export const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: "#F5F6FB",
    },
    content: {
        paddingHorizontal: 20,
        paddingTop: 24,
        paddingBottom: 40,
        gap: 16,
    },
    center: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#F8FAFC',
        padding: 24,
    },
    errorText: {
        color: '#DC2626',
        fontSize: 15,
        textAlign: 'center',
    },
    cardRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    cardTitle: {
        fontSize: 16,
        fontWeight: '600',
        color: '#0F172A',
    },
    date: {
        fontSize: 12,
        color: '#64748B',
        marginTop: 6,
    },
    scoreContainer: {
        alignItems: 'flex-end',
    },
    score: {
        fontSize: 22,
        fontWeight: '700',
        color: '#0F172A',
    },
    percentage: {
        fontSize: 12,
        color: '#64748B',
        marginTop: 4,
    },
    progressTrack: {
        height: 6,
        borderRadius: 6,
        backgroundColor: '#E2E8F0',
        overflow: 'hidden',
        marginTop: 18,
    },
    progressFill: {
        height: '100%',
        borderRadius: 6,
    },
});