
import { StyleSheet } from 'react-native';

export const historyStyles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#F8FAFC',
    },
    content: {
        paddingHorizontal: 20,
        paddingTop: 24,
        paddingBottom: 32,
    },
    header: {
        marginBottom: 28,
    },
    title: {
        fontSize: 28,
        fontWeight: '700',
        color: '#0F172A',
    },
    subtitle: {
        fontSize: 14,
        color: '#64748B',
        marginTop: 6,
    },
    sectionHeader: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        marginBottom: 16,
    },
    sectionTitle: {
        fontSize: 16,
        fontWeight: '600',
        color: '#334155',
    },
    count: {
        fontSize: 13,
        color: '#64748B',
    },
    card: {
        backgroundColor: '#FFFFFF',
        borderRadius: 16,
        padding: 18,
        marginBottom: 12,
        borderWidth: 1,
        borderColor: '#E2E8F0',
        shadowColor: '#0F172A',
        shadowOffset: { width: 0, height: 3 },
        shadowOpacity: 0.04,
        shadowRadius: 10,
        elevation: 2,
    },
    cardRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    cardTitle: {
        fontSize: 16,
        fontWeight: '600',
        color: '#0F172A',
    },
    date: {
        fontSize: 12,
        color: '#64748B',
        marginTop: 6,
    },
    scoreContainer: {
        alignItems: 'flex-end',
    },
    score: {
        fontSize: 22,
        fontWeight: '700',
        color: '#0F172A',
    },
    percentage: {
        fontSize: 12,
        color: '#64748B',
        marginTop: 4,
    },
    progressTrack: {
        height: 6,
        borderRadius: 6,
        backgroundColor: '#E2E8F0',
        overflow: 'hidden',
        marginTop: 18,
    },
    progressFill: {
        height: '100%',
        borderRadius: 6,
    },
    center: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#F8FAFC',
        padding: 24,
    },
    message: {
        color: '#64748B',
        fontSize: 15,
        textAlign: 'center',
    },
    errorText: {
        color: '#DC2626',
        fontSize: 15,
        textAlign: 'center',
    },
});
