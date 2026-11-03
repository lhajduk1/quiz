import { QuizAttemptQuestion } from "@/types/quiz";
import { StyleSheet, View, Text } from "react-native";

type QuestionReviewProps = {
    question: QuizAttemptQuestion,
    index: number
}

export default function QuestionReview({ question, index }: QuestionReviewProps) {
    const isCorrect = question.answeredCorrectly;
    const selectedAnswer = question.answersSnapshot.find((answer) => answer.id === question.selectedAnswerId);
    const correctAnswer = question.answersSnapshot.find((answer) => answer.id === question.correctAnswerId);

    return (
        <View style={styles.questionCard}>
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
                {question.questionSnapshot}
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
                    Your answer: {selectedAnswer?.text ?? "No answer selected"}
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
}

const styles = StyleSheet.create({
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