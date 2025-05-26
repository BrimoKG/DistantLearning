
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест по квадратным уравнениям</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .question {
            margin-bottom: 25px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        .question h3 {
            margin-top: 0;
            color: #2980b9;
        }
        .options {
            margin: 15px 0;
        }
        .option {
            margin: 10px 0;
            padding: 10px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .option:hover {
            background: #e3f2fd;
            border-color: #3498db;
        }
        .option.selected {
            background: #bbdefb;
            border-color: #1976d2;
        }
        .option.correct {
            background: #c8e6c9;
            border-color: #388e3c;
        }
        .option.incorrect {
            background: #ffcdd2;
            border-color: #d32f2f;
        }
        .math {
            font-family: "Cambria Math", serif;
            background-color: #f8f9fa;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            display: block;
            margin: 30px auto 0;
        }
        button:hover {
            background: #2980b9;
        }
        button:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        .result {
            text-align: center;
            font-size: 20px;
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        .success {
            background: #dff0d8;
            color: #3c763d;
        }
        .failure {
            background: #f2dede;
            color: #a94442;
        }
        .explanation {
            margin-top: 15px;
            padding: 10px;
            background: #e3f2fd;
            border-radius: 5px;
            display: none;
        }
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }
            .question {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Тест по квадратным уравнениям</h1>
        
        <div id="quiz-container">
            <div class="question" id="q1">
                <h3>1. Какое из следующих уравнений является квадратным?</h3>
                <div class="options">
                    <div class="option" onclick="selectOption(this, 'a', 'q1')">A) <span class="math">3x + 5 = 0</span></div>
                    <div class="option" onclick="selectOption(this, 'b', 'q1')">B) <span class="math">2x² - 5x + 3 = 0</span></div>
                    <div class="option" onclick="selectOption(this, 'c', 'q1')">C) <span class="math">x³ - 4x² + x = 0</span></div>
                    <div class="option" onclick="selectOption(this, 'd', 'q1')">D) <span class="math">5/x + x = 2</span></div>
                </div>
                <div class="explanation" id="exp-q1">
                    Квадратное уравнение имеет вид <span class="math">ax² + bx + c = 0</span>, где <span class="math">a ≠ 0</span>.
                    Только вариант B соответствует этому определению.
                </div>
            </div>
            
            <div class="question" id="q2">
                <h3>2. Решите уравнение: <span class="math">x² - 5x + 6 = 0</span></h3>
                <div class="options">
                    <div class="option" onclick="selectOption(this, 'a', 'q2')">A) <span class="math">x = 2</span> и <span class="math">x = 3</span></div>
                    <div class="option" onclick="selectOption(this, 'b', 'q2')">B) <span class="math">x = -2</span> и <span class="math">x = -3</span></div>
                    <div class="option" onclick="selectOption(this, 'c', 'q2')">C) <span class="math">x = 1</span> и <span class="math">x = 6</span></div>
                    <div class="option" onclick="selectOption(this, 'd', 'q2')">D) <span class="math">x = 0.5</span> и <span class="math">x = 3</span></div>
                </div>
                <div class="explanation" id="exp-q2">
                    Уравнение можно решить разложением на множители: <span class="math">(x-2)(x-3) = 0</span>.
                    Корни: <span class="math">x = 2</span> и <span class="math">x = 3</span>.
                </div>
            </div>
            
            <div class="question" id="q3">
                <h3>3. Чему равен дискриминант уравнения <span class="math">2x² - 4x + 2 = 0</span>?</h3>
                <div class="options">
                    <div class="option" onclick="selectOption(this, 'a', 'q3')">A) 0</div>
                    <div class="option" onclick="selectOption(this, 'b', 'q3')">B) 8</div>
                    <div class="option" onclick="selectOption(this, 'c', 'q3')">C) -8</div>
                    <div class="option" onclick="selectOption(this, 'd', 'q3')">D) 16</div>
                </div>
                <div class="explanation" id="exp-q3">
                    Дискриминант вычисляется по формуле: <span class="math">D = b² - 4ac = (-4)² - 4*2*2 = 16 - 16 = 0</span>.
                </div>
            </div>
            
            <div class="question" id="q4">
                <h3>4. Сколько действительных корней имеет уравнение <span class="math">x² + 4 = 0</span>?</h3>
                <div class="options">
                    <div class="option" onclick="selectOption(this, 'a', 'q4')">A) 0</div>
                    <div class="option" onclick="selectOption(this, 'b', 'q4')">B) 1</div>
                    <div class="option" onclick="selectOption(this, 'c', 'q4')">C) 2</div>
                    <div class="option" onclick="selectOption(this, 'd', 'q4')">D) 3</div>
                </div>
                <div class="explanation" id="exp-q4">
                    Дискриминант: <span class="math">D = 0² - 4*1*4 = -16 < 0</span>.
                    При D < 0 уравнение не имеет действительных корней (но имеет два комплексных корня).
                </div>
            </div>
            
            <div class="question" id="q5">
                <h3>5. Какой метод наиболее эффективен для решения уравнения <span class="math">x² - 6x + 9 = 0</span>?</h3>
                <div class="options">
                    <div class="option" onclick="selectOption(this, 'a', 'q5')">A) Разложение на множители</div>
                    <div class="option" onclick="selectOption(this, 'b', 'q5')">B) Квадратная формула</div>
                    <div class="option" onclick="selectOption(this, 'c', 'q5')">C) Выделение полного квадрата</div>
                    <div class="option" onclick="selectOption(this, 'd', 'q5')">D) Любой из вышеперечисленных</div>
                </div>
                <div class="explanation" id="exp-q5">
                    Это уравнение является полным квадратом: <span class="math">(x-3)² = 0</span>.
                    Все методы приведут к правильному ответу, но разложение на множители или выделение полного квадрата будут самыми быстрыми.
                </div>
            </div>
            
            <button id="submit-btn" onclick="checkAnswers()" disabled>Проверить ответы</button>
            <div class="result" id="result"></div>
        </div>
    </div>

    <script>
        const correctAnswers = {
            q1: 'b',
            q2: 'a',
            q3: 'a',
            q4: 'a',
            q5: 'd'
        };
        
        let selectedOptions = {};
        let submitEnabled = false;
        
        function selectOption(element, optionId, questionId) {
            // Remove previous selection in this question
            const question = document.getElementById(questionId);
            const options = question.querySelectorAll('.option');
            options.forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Select new option
            element.classList.add('selected');
            selectedOptions[questionId] = optionId;
            
            // Enable submit button if all questions are answered
            checkAllAnswered();
        }
        
        function checkAllAnswered() {
            const allQuestions = document.querySelectorAll('.question');
            let allAnswered = true;
            
            allQuestions.forEach(question => {
                const questionId = question.id;
                if (!selectedOptions[questionId]) {
                    allAnswered = false;
                }
            });
            
            submitEnabled = allAnswered;
            document.getElementById('submit-btn').disabled = !allAnswered;
        }
        
        function checkAnswers() {
            if (!submitEnabled) return;
            
            let score = 0;
            const totalQuestions = Object.keys(correctAnswers).length;
            
            // Check each question
            for (const questionId in correctAnswers) {
                const selectedOption = selectedOptions[questionId];
                const correctOption = correctAnswers[questionId];
                
                const questionElement = document.getElementById(questionId);
                const options = questionElement.querySelectorAll('.option');
                
                // Mark correct and incorrect answers
                options.forEach(option => {
                    const optionId = option.getAttribute('onclick').split("'")[1];
                    if (optionId === correctOption) {
                        option.classList.add('correct');
                    } else if (optionId === selectedOption && selectedOption !== correctOption) {
                        option.classList.add('incorrect');
                    }
                });
                
                // Show explanation
                document.getElementById('exp-' + questionId).style.display = 'block';
                
                // Update score
                if (selectedOption === correctOption) {
                    score++;
                }
            }
            
            // Show result
            const resultElement = document.getElementById('result');
            resultElement.style.display = 'block';
            
            if (score === totalQuestions) {
                resultElement.textContent = `Поздравляем! Вы ответили правильно на все ${totalQuestions} вопросов!`;
                resultElement.className = 'result success';
            } else {
                resultElement.textContent = `Вы ответили правильно на ${score} из ${totalQuestions} вопросов.`;
                resultElement.className = 'result failure';
            }
            
            // Disable further selection
            const allOptions = document.querySelectorAll('.option');
            allOptions.forEach(option => {
                option.style.cursor = 'default';
                option.onclick = null;
            });
            
            // Change button text
            document.getElementById('submit-btn').textContent = 'Тест завершен';
            document.getElementById('submit-btn').disabled = true;
        }
    </script>
</body>
</html>

