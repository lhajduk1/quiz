import { Text, ScrollView, StyleSheet } from "react-native";
import { useQuizContext } from "@/hooks/useQuizContext";
import QuizResults from "@/components/quiz/QuizResults";
import { SafeAreaView } from "react-native-safe-area-context";
import { useRouter } from "expo-router";
import QuizButton from "@/components/quiz/QuizButton";

export default function Results() {
    const router = useRouter();

    const { quizResult, setQuizResult, questions, setQuestions } = useQuizContext();

    function handleRestart(): void {
        setQuizResult(null);
        setQuestions([]);
        router.replace('/quiz');
    }

    if (!quizResult) {
        return <Text>No quiz result available.</Text>
    }

    return (
        <SafeAreaView style={styles.container}>
            <ScrollView contentContainerStyle={styles.content}>
                <QuizResults
                    questions={questions}
                    result={quizResult}
                />
                <QuizButton
                    label="Restart"
                    onPress={handleRestart}
                />
            </ScrollView>
        </SafeAreaView>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: "#F5F6FB",
    },
    content: {
        flexGrow: 1,
        paddingHorizontal: 20,
        paddingTop: 48,
        paddingBottom: 36,
        gap: 24,
    },
});
