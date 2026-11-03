import { getQuizAttempts } from "@/services/quizService";
import { QuizAttemptHistoryItem } from "@/types/quiz";
import axios from "axios";
import { useEffect, useState } from "react";
import { View, Text, ActivityIndicator, FlatList, Pressable } from "react-native";
import { historyStyles as styles } from "@/styles/historyStyles";
import { SafeAreaView } from "react-native-safe-area-context";
import { useRouter } from "expo-router";


export default function History() {
    const router = useRouter();
    const [attempts, setAttempts] = useState<QuizAttemptHistoryItem[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    async function loadQuizAttempts(signal: AbortSignal) {
        setIsLoading(true);
        setError(null);

        try {
            const data = await getQuizAttempts(signal);

            if (signal.aborted) {
                return;
            }

            setAttempts(data);

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

        loadQuizAttempts(controller.signal);

        return () => {
            controller.abort();
        }

    }, []);


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

    return (
        <SafeAreaView style={styles.container}>
            <FlatList
                data={attempts}
                keyExtractor={(item) => item.id.toString()}
                contentContainerStyle={styles.content}
                ListHeaderComponent={
                    <>
                        <View style={styles.header}>
                            <Text style={styles.title}>The History of Quizzes</Text>
                            <Text style={styles.subtitle}>
                                Your previous attempts and results
                            </Text>
                        </View>

                        <View style={styles.sectionHeader}>
                            <Text style={styles.sectionTitle}>
                                Last attempts
                            </Text>
                            <Text style={styles.count}>
                                Total: {attempts.length}
                            </Text>
                        </View>
                    </>
                }
                ListEmptyComponent={
                    <Text style={styles.message}>
                        The history section is empty. Take your first quiz!
                    </Text>
                }
                renderItem={({ item }) => {
                    const percentage = item.questionsCount > 0
                        ? Math.round(
                            ((item.correctAnswersCount ?? 0) / item.questionsCount) * 100
                        )
                        : 0;

                    return (
                        <Pressable
                            style={styles.card}
                            onPress={() => router.push({
                                pathname: '/history/[id]',
                                params: { id: item.id }
                            })}
                        >
                            <View style={styles.cardRow}>
                                <View>
                                    <Text style={styles.cardTitle}>
                                        Attempt #{item.id}
                                    </Text>
                                    <Text style={styles.date}>
                                        {new Date(item.finishedAt).toLocaleDateString('pl-PL')}
                                    </Text>
                                </View>

                                <View style={styles.scoreContainer}>
                                    <Text style={styles.score}>
                                        {item.correctAnswersCount}/{item.questionsCount}
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
                        </Pressable>
                    );
                }}
            />
        </SafeAreaView>
    );

}