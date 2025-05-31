<script setup lang="ts">
    import { computed, onMounted, ref, watch } from 'vue';
    import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
    import Card from '@/components/ui/card/Card.vue'
    import CardHeader from '@/components/ui/card/CardHeader.vue';
    import CardTitle from '@/components/ui/card/CardTitle.vue';
    import CardContent from '@/components/ui/card/CardContent.vue';
    import Button from '@/components/ui/button/Button.vue';

    const page = usePage()

    const questions = ref([])
    const currentQuestionIndex = ref(0)
    const loading = ref(false)
    const correctScore = ref(0)
    const incorrectScore = ref(0)
    const completed = ref(false)

    onMounted(() => {
        getQuestions()
    })

    const form = useForm({
        id: '',
        score: 0,
        startTime: '',
        endTime: '',
    })

    const currentQuestion = computed(() => {
        return questions.value[currentQuestionIndex.value]
    });

    const remainingCount = computed(() => {
        return questions.value.length - currentQuestionIndex.value
    });

    const randomAnswers = computed(() => {
       return [currentQuestion.value.correctAnswer,
            ...currentQuestion.value.incorrectAnswers].sort((a,b)=> a < b ? -1 : 1)
    });

    const finalScore = computed(() => {
        return correctScore.value * 10
    });

    const hasCompleted = computed(() => {
        return completed.value = true ? remainingCount.value <= 0 : remainingCount.value >= 0
    });

    const user = computed(() => {
        return page.props.auth.user
    });

    const getQuestions = async () => {
        loading.value = true
        const resp = await axios.get('https://the-trivia-api.com/v2/questions?limit=10&difficulties=medium')
        questions.value = resp.data
        loading.value = false
        console.log(resp.data)
    };

    const guess = (answer) => {
        if (answer === currentQuestion.value.correctAnswer) {
            correctScore.value++
        } else {
            incorrectScore.value++
        }
        currentQuestionIndex.value++
    };

    const resetQuiz = () => {
        completed.value = false
        questions.value = []
        correctScore.value = 0
        incorrectScore.value = 0
        currentQuestionIndex.value = 0
        getQuestions()
    };

    watch(currentQuestionIndex, (newIndex) => {
        if (newIndex === 10) {
            if (!user.value) {
               stop()
            } else {
               submit()
            }
        }
    });

    const stop = () => {
        form.score = correctScore.value
    };

    const submit = () => {
        stop()
        form.post(route('stats.store'))
    };
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8"
        :class="{ 'bg-[url(/storage/images/celebration-19390_256.gif)] bg-no-repeat bg-cover' : hasCompleted }"
    >
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <Card class="my-auto p-6 w-3/4" v-if="currentQuestion">
            <CardHeader>
                <CardTitle class="text-center">
                    <div class="flex justify-center text-xl space-x-5 mb-6">
                        <div class="flex justify-between w-full">
                            <p>Duration:</p>
                            <p>00:00</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center text-xl space-y-5 lg:flex-row lg:justify-center lg:space-y-0 lg:space-x-10">
                        <div class="flex flex-col items-end w-1/4">
                            <div class="flex justify-between space-x-5 w-full">
                                <p>Total:</p>
                                <p>{{ questions.length }}</p>
                            </div>
                            <div class="flex justify-between space-x-5 w-full">
                                <p>Remaining:</p>
                                <p>{{ remainingCount }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end w-1/4">
                            <div class="flex justify-between space-x-5 text-green-700 w-full">
                                <p>Correct:</p>
                                <p>{{ correctScore }}</p>
                            </div>
                            <div class="flex justify-between space-x-5 text-red-600 w-full">
                                <p>Incorrect:</p>
                                <p>{{ incorrectScore }}</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-xl mt-4">
                        {{ currentQuestion.question.text }}
                    </p>
                </CardTitle>
            </CardHeader>

            <div class="flex flex-col justify-center space-y-6 mx-auto w-full md:w-1/2">
                <button type="button"
                    class="rounded-md font-medium transition-all bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-auto px-4 py-2 cursor-pointer"
                    v-for="(answer, index) in randomAnswers"
                    :key="index"
                    @click.prevent="guess(answer)"
                >
                    {{ answer }}
                </button>
            </div>
        </Card>

        <Card class="my-auto p-6 w-3/4" v-else-if="loading">
            <CardHeader>
                <CardTitle class="text-center">
                    <p>...loading</p>
                </CardTitle>
            </CardHeader>
        </Card>

        <Card class="my-auto p-6 w-3/4" v-else>
            <CardHeader>
                <CardTitle class="text-center text-xl">
                    <p>Congrats, here is your final score!</p>
                    <p>{{ finalScore }}%</p>
                </CardTitle>
            </CardHeader>
            <CardContent class="text-center">
                <Button
                    class="cursor-pointer"
                    @click.prevent="resetQuiz"
                >
                        Play Again!
                </Button>
            </CardContent>
        </Card>
    </div>
</template>
