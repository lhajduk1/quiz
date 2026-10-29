import QuizProvider from "@/providers/QuizProvider";
import { Stack } from "expo-router";

export default function RootLayout() {
  return (
    <QuizProvider>
      <Stack
        screenOptions={{
          headerShown: false,
        }}
      />
    </QuizProvider>
  );
}