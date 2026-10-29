import { Pressable, Text, StyleSheet } from "react-native"

type QuizButtonProps = {
    label: string;
    disabled?: boolean;
    onPress: () => void;
    variant?: "primary" | "secondary";
}

export default function QuizButton({ label, disabled, onPress, variant = "primary" }: QuizButtonProps) {
    const isSecondary = variant === "secondary";

    return (
        <Pressable
            onPress={onPress}
            disabled={disabled}
            accessibilityRole="button"
            accessibilityState={{ disabled }}
            style={({ pressed }) => [
                styles.button,
                isSecondary ? styles.secondary : styles.primary,
                pressed && !disabled && styles.pressed,
                disabled && styles.disabled,
            ]}
        >
            <Text
                style={[
                    styles.label,
                    isSecondary ? styles.secondaryLabel : styles.primaryLabel,
                ]}
            >
                {label}
            </Text>
        </Pressable>
    )
}

const styles = StyleSheet.create({
    button: {
        minHeight: 54,
        paddingHorizontal: 22,
        paddingVertical: 14,
        borderRadius: 16,
        alignItems: "center",
        justifyContent: "center",
    },
    primary: {
        backgroundColor: "#7359E8",
    },
    secondary: {
        backgroundColor: "#FFFFFF",
        borderWidth: 1,
        borderColor: "#E1E3ED",
    },
    label: {
        fontSize: 15,
        fontWeight: "700",
    },
    primaryLabel: {
        color: "#FFFFFF",
    },
    secondaryLabel: {
        color: "#555A70",
    },
    pressed: {
        opacity: 0.82,
    },
    disabled: {
        opacity: 0.45,
    },
});