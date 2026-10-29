
import { StyleSheet, Text, View } from "react-native";
import type { QuizQuestion, QuizResult } from "@/types/quiz";

type QuizResultsProps = {
    questions: QuizQuestion[];
    result: QuizResult;
};

export default function QuizResults({
    questions,
    result,
}: QuizResultsProps) {
    const percentage =
        result.questionsCount > 0
            ? Math.round(
                (result.correctAnswersCount / result.questionsCount) * 100
            )
            : 0;

    return (
        <View style={styles.container}>
            <View style={styles.summaryCard}>
                <View style={styles.trophyCircle}>
                    <Text style={styles.trophy}>🏆</Text>
                </View>

                <Text style={styles.title}>Quiz completed!</Text>
                <Text style={styles.subtitle}>
                    Here's how you performed
                </Text>

                <Text style={styles.score}>
                    {result.correctAnswersCount}
                    <Text style={styles.scoreTotal}>
                        {" / "}{result.questionsCount}
                    </Text>
                </Text>

                <View style={styles.progressTrack}>
                    <View
                        style={[
                            styles.progressFill,
                            { width: `${percentage}%` },
                        ]}
                    />
                </View>

                <Text style={styles.percentage}>
                    {percentage}% correct answers
                </Text>
            </View>

            <Text style={styles.sectionTitle}>Your answers</Text>

            {result.questions.map((questionResult, index) => {
                const question = questions.find(
                    (item) => item.id === questionResult.id
                );

                if (!question) {
                    return null;
                }

                const selectedAnswer = question.answers.find(
                    (answer) => answer.id === questionResult.selectedAnswerId
                );

                const correctAnswer = question.answers.find(
                    (answer) => answer.id === questionResult.correctAnswerId
                );

                const isCorrect = questionResult.answeredCorrectly;

                return (
                    <View key={questionResult.id} style={styles.questionCard}>
                        <View style={styles.questionHeader}>
                            <Text style={styles.questionNumber}>
                                QUESTION {String(index + 1).padStart(2, "0")}
                            </Text>

                            <Text
                                style={[
                                    styles.status,
                                    isCorrect ? styles.correctStatus : styles.incorrectStatus,
                                ]}
                            >
                                {isCorrect ? "✓ Correct" : "✕ Incorrect"}
                            </Text>
                        </View>

                        <Text style={styles.questionText}>
                            {question.question}
                        </Text>

                        <View
                            style={[
                                styles.answerBox,
                                isCorrect ? styles.correctBox : styles.incorrectBox,
                            ]}
                        >
                            <Text
                                style={[
                                    styles.answerText,
                                    isCorrect ? styles.correctText : styles.incorrectText,
                                ]}
                            >
                                Your answer: {selectedAnswer?.text ?? "Unavailable"}
                            </Text>
                        </View>

                        {!isCorrect && (
                            <View style={[styles.answerBox, styles.correctBox]}>
                                <Text style={[styles.answerText, styles.correctText]}>
                                    Correct answer: {correctAnswer?.text ?? "Unavailable"}
                                </Text>
                            </View>
                        )}
                    </View>
                );
            })}
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        paddingHorizontal: 20,
        paddingTop: 48,
        paddingBottom: 36,
        gap: 16,
    },
    summaryCard: {
        backgroundColor: "#FFFFFF",
        borderRadius: 24,
        padding: 24,
        alignItems: "center",
        gap: 12,
        borderWidth: 1,
        borderColor: "#E9EAF2",
    },
    trophyCircle: {
        width: 64,
        height: 64,
        borderRadius: 32,
        backgroundColor: "#EDE9FF",
        alignItems: "center",
        justifyContent: "center",
    },
    trophy: {
        fontSize: 30,
    },
    title: {
        fontSize: 24,
        fontWeight: "800",
        color: "#20243A",
        textAlign: "center",
    },
    subtitle: {
        fontSize: 14,
        color: "#85899B",
        textAlign: "center",
    },
    score: {
        marginTop: 8,
        fontSize: 44,
        fontWeight: "800",
        color: "#7359E8",
    },
    scoreTotal: {
        fontSize: 28,
        fontWeight: "600",
        color: "#85899B",
    },
    progressTrack: {
        width: "100%",
        height: 8,
        backgroundColor: "#E4E6F0",
        borderRadius: 8,
        overflow: "hidden",
    },
    progressFill: {
        height: "100%",
        backgroundColor: "#7359E8",
        borderRadius: 8,
    },
    percentage: {
        fontSize: 14,
        fontWeight: "700",
        color: "#7359E8",
    },
    sectionTitle: {
        fontSize: 20,
        fontWeight: "700",
        color: "#20243A",
        marginTop: 8,
        marginBottom: 4,
    },
    questionCard: {
        backgroundColor: "#FFFFFF",
        borderRadius: 20,
        padding: 20,
        gap: 14,
        borderWidth: 1,
        borderColor: "#E9EAF2",
    },
    questionHeader: {
        flexDirection: "row",
        justifyContent: "space-between",
        alignItems: "center",
        gap: 8,
    },
    questionNumber: {
        fontSize: 11,
        fontWeight: "700",
        letterSpacing: 1.2,
        color: "#85899B",
    },
    status: {
        fontSize: 12,
        fontWeight: "700",
    },
    correctStatus: {
        color: "#22976D",
    },
    incorrectStatus: {
        color: "#D45A5A",
    },
    questionText: {
        fontSize: 17,
        lineHeight: 25,
        fontWeight: "700",
        color: "#20243A",
    },
    answerBox: {
        padding: 14,
        borderRadius: 12,
    },
    correctBox: {
        backgroundColor: "#E8F8F0",
    },
    incorrectBox: {
        backgroundColor: "#FFF0F0",
    },
    answerText: {
        fontSize: 14,
        lineHeight: 21,
        fontWeight: "500",
    },
    correctText: {
        color: "#177A54",
    },
    incorrectText: {
        color: "#B73D3D",
    },
});
