import { View, Text, StyleSheet, Pressable } from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import QuizButton from "@/components/quiz/QuizButton";
import { Link, useRouter } from "expo-router";
import { FontAwesome6 } from "@react-native-vector-icons/fontawesome6";

export default function Home() {
    const router = useRouter();

    function handleStartQuiz(): void {
        router.push('/quiz');
    }

    return (
        <SafeAreaView style={styles.container}>
            <View style={styles.content}>
                <View style={styles.header}>
                    <Text style={styles.eyebrow}>WELCOME TO</Text>
                    <Text style={styles.title}>Quiz App</Text>
                    <Text style={styles.subtitle}>
                        Test your knowledge and see how much
                        you've learned.
                    </Text>
                </View>

                <View style={styles.card}>
                    <Text style={styles.cardIcon}>🧠</Text>
                    <Text style={styles.cardTitle}>
                        Ready for a challenge?
                    </Text>
                    <Text style={styles.cardDescription}>
                        Answer questions, track your progress
                        and discover your score.
                    </Text>
                </View>

                <View>
                    <QuizButton
                        label="Start Quiz"
                        onPress={handleStartQuiz}
                    />

                    <Pressable
                        onPress={() => router.push('/history')}
                        style={({ pressed }) => [
                            styles.historyButton,
                            pressed && styles.historyButtonPressed,
                        ]}
                    >
                        <FontAwesome6 name="clock-rotate-left" iconStyle="solid" size={16} color="#4F46E5" />
                        <Text style={styles.historyButtonText}>
                            Historia podejść
                        </Text>
                    </Pressable>
                </View>
            </View>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: "#F5F6FB",
    },
    content: {
        flex: 1,
        paddingHorizontal: 24,
        paddingVertical: 40,
        justifyContent: "center",
        gap: 32,
    },
    header: {
        gap: 12,
    },
    eyebrow: {
        fontSize: 12,
        fontWeight: "700",
        letterSpacing: 2,
        color: "#7359E8",
    },
    title: {
        fontSize: 42,
        fontWeight: "800",
        color: "#20243A",
    },
    subtitle: {
        fontSize: 16,
        lineHeight: 25,
        color: "#85899B",
    },
    card: {
        backgroundColor: "#FFFFFF",
        borderRadius: 24,
        padding: 28,
        gap: 14,
        alignItems: "center",
    },
    cardIcon: {
        fontSize: 56,
    },
    cardTitle: {
        fontSize: 22,
        fontWeight: "800",
        color: "#20243A",
        textAlign: "center",
    },
    cardDescription: {
        fontSize: 15,
        lineHeight: 23,
        color: "#85899B",
        textAlign: "center",
    },
    historyButton: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        gap: 10,
        width: '100%',
        backgroundColor: '#FFFFFF',
        borderWidth: 1,
        borderColor: '#C7D2FE',
        borderRadius: 14,
        paddingVertical: 15,
        paddingHorizontal: 20,
        marginTop: 16,
    },
    historyButtonPressed: {
        backgroundColor: '#EEF2FF',
        opacity: 0.85,
    },
    historyButtonText: {
        color: '#4F46E5',
        fontSize: 15,
        fontWeight: '600',
    },

});