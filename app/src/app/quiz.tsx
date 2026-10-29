import { View, StyleSheet, Text, ScrollView } from "react-native";
import QuizQuestion from "@/components/quiz/Question";
import QuizButton from "@/components/quiz/QuizButton";
import { SafeAreaView } from "react-native-safe-area-context";
import { useQuiz } from "@/hooks/useQuiz";
import { useRouter } from "expo-router";

export default function Quiz() {
  const router = useRouter();

  const {
    questions,
    selectedAnswers,
    currentQuestionIndex,
    currentQuestion,
    isLastQuestion,
    hasPreviousQuestion,
    isLoading,
    loadingError,
    submitError,
    isSubmitting,
    selectAnswer,
    nextQuestion,
    previousQuestion,
    loadQuestions,
    handleSubmitQuiz
  } = useQuiz();

  async function handleFinish(): Promise<void> {
    const success = await handleSubmitQuiz();

    if (success) {
      router.replace('/results');
    }
  }

  if (isLoading) {
    return (
      <View style={styles.container}>
        <Text>Loading questions...</Text>
      </View>
    )
  }

  if (loadingError !== null) {
    return (
      <View style={styles.container}>
        <Text>{loadingError}</Text>
        <QuizButton
          label="Try again"
          onPress={loadQuestions}
        />
      </View>
    )
  }

  if (questions.length === 0) {
    return (
      <View style={styles.container}>
        <Text>No questions available.</Text>
      </View>
    )
  }

  const progress = ((currentQuestionIndex + 1) / questions.length) * 100;

  return (
    <SafeAreaView style={styles.container}>
      <ScrollView
        contentContainerStyle={styles.content}
      >
        <View style={styles.header}>
          <Text style={styles.eyebrow}>REACT NATIVE QUIZ</Text>
          <Text style={styles.title}>Test your knowledge</Text>
          <Text style={styles.subtitle}>
            Choose the correct answer to each question.
          </Text>
        </View>

        <View style={styles.progressSection}>
          <View style={styles.progressLabels}>
            <Text style={styles.progressText}>
              Question {currentQuestionIndex + 1} of {questions.length}
            </Text>
            <Text style={styles.progressPercent}>
              {Math.round(progress)}%
            </Text>
          </View>

          <View style={styles.progressTrack}>
            <View
              style={[styles.progressFill, { width: `${progress}%` }]}
            />
          </View>
        </View>

        <QuizQuestion
          key={currentQuestion.id}
          index={currentQuestionIndex}
          question={currentQuestion.question}
          answers={currentQuestion.answers}
          selectedAnswerId={selectedAnswers[currentQuestion.id] ?? null}
          onAnswerSelect={(answerId) => {
            selectAnswer(currentQuestion.id, answerId);
          }}
        />

        <View style={styles.navigation}>
          <View style={styles.previousButton}>
            <QuizButton
              label="Previous"
              variant="secondary"
              disabled={!hasPreviousQuestion}
              onPress={previousQuestion}
            />
          </View>

          <View style={styles.nextButton}>
            <QuizButton
              label={
                isSubmitting
                  ? "Submitting..."
                  : isLastQuestion
                    ? "Finish quiz"
                    : "Next"
              }
              disabled={
                isSubmitting ||
                selectedAnswers[currentQuestion.id] === undefined
              }
              onPress={
                isLastQuestion
                  ? handleFinish
                  : nextQuestion
              }
            />
          </View>
        </View>

        {submitError && (
          <Text style={styles.errorText}>{submitError}</Text>
        )}
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
  header: {
    gap: 7,
  },
  eyebrow: {
    fontSize: 12,
    fontWeight: "700",
    letterSpacing: 1.5,
    color: "#7359E8",
  },
  title: {
    fontSize: 30,
    fontWeight: "800",
    color: "#20243A",
  },
  subtitle: {
    fontSize: 15,
    lineHeight: 22,
    color: "#85899B",
  },
  progressSection: {
    gap: 12,
  },
  progressLabels: {
    flexDirection: "row",
    justifyContent: "space-between",
  },
  progressText: {
    fontSize: 14,
    fontWeight: "600",
    color: "#555A70",
  },
  progressPercent: {
    fontSize: 14,
    fontWeight: "700",
    color: "#7359E8",
  },
  progressTrack: {
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
  navigation: {
    flexDirection: "row",
    gap: 12,
  },
  previousButton: {
    flex: 1,
  },
  nextButton: {
    flex: 1,
  },
  errorText: {
    fontSize: 14,
    color: "#DC3545",
    textAlign: "center",
  },
});

