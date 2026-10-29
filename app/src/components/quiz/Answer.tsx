import { Text, Pressable, StyleSheet, View } from "react-native"

type AnswerProps = {
    text: string;
    isSelected: boolean;
    onSelect: () => void;
}

export default function Answer({ text, isSelected, onSelect }: AnswerProps) {
    return (
        <Pressable
            onPress={onSelect}
            accessibilityRole="radio"
            accessibilityState={{ checked: isSelected }}
            style={({ pressed }) => [
                styles.answer,
                isSelected && styles.selectedAnswer,
                pressed && styles.pressed,
            ]}
        >
            <View
                style={[
                    styles.radio,
                    isSelected && styles.selectedRadio,
                ]}
            >
                {isSelected && <View style={styles.radioDot} />}
            </View>

            <Text
                style={[
                    styles.answerText,
                    isSelected && styles.selectedText,
                ]}
            >
                {text}
            </Text>
        </Pressable>
    );
}

const styles = StyleSheet.create({
    answer: {
        flexDirection: "row",
        alignItems: "center",
        gap: 14,
        minHeight: 62,
        paddingHorizontal: 16,
        paddingVertical: 14,
        borderWidth: 1.5,
        borderColor: "#E4E6EF",
        borderRadius: 16,
        backgroundColor: "#FFFFFF",
    },
    selectedAnswer: {
        backgroundColor: "#F1EEFF",
        borderColor: "#7359E8",
    },
    pressed: {
        opacity: 0.75,
    },
    radio: {
        width: 22,
        height: 22,
        borderRadius: 11,
        borderWidth: 2,
        borderColor: "#C6C9D7",
        alignItems: "center",
        justifyContent: "center",
    },
    selectedRadio: {
        borderColor: "#7359E8",
    },
    radioDot: {
        width: 10,
        height: 10,
        borderRadius: 5,
        backgroundColor: "#7359E8",
    },
    answerText: {
        flex: 1,
        fontSize: 15,
        lineHeight: 22,
        fontWeight: "500",
        color: "#3C4055",
    },
    selectedText: {
        color: "#4E3BB6",
        fontWeight: "600",
    },
});