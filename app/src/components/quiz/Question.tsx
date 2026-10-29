import { Text, View, StyleSheet } from "react-native"
import QuizAnswer from "./Answer"
import type { Answer } from "@/types/quiz"

type QuestionProps = {
    index: number;
    question: string;
    answers: Array<Answer>;
    selectedAnswerId: number | null;
    onAnswerSelect: (answerId: number) => void;
}

export default function Question({ index, question, answers, selectedAnswerId, onAnswerSelect }: QuestionProps) {
    return (
        <View style={styles.card}>
            <Text style={styles.eyebrow}>
                QUESTION {String(index + 1).padStart(2, "0")}
            </Text>

            <Text style={styles.question}>{question}</Text>

            <View style={styles.answers}>
                {answers.map((answer) => (
                    <QuizAnswer
                        key={answer.id}
                        text={answer.text}
                        isSelected={selectedAnswerId === answer.id}
                        onSelect={() => onAnswerSelect(answer.id)}
                    />
                ))}
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    card: {
        backgroundColor: "#FFFFFF",
        borderRadius: 24,
        padding: 22,
        borderWidth: 1,
        borderColor: "#E9EAF2",
        gap: 22,
    },
    eyebrow: {
        fontSize: 12,
        fontWeight: "700",
        letterSpacing: 1.4,
        color: "#7662DB",
    },
    question: {
        fontSize: 23,
        lineHeight: 32,
        fontWeight: "700",
        color: "#20243A",
    },
    answers: {
        gap: 12,
    },
});