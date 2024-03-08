
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        #container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            max-width: 1000px;
            margin: 50px auto;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .option {
            margin-bottom: 10px;
        }

        .option input {
            margin-right: 10px;
        }

        #checkButton {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0 auto;
            width: 80%;
        }

        #checkButton:hover {
            background-color: #0056b3;
        }
</style>

<header class="page-header gradient" id="gradient" style="margin-top: 0 " ></header>
<style>
    .headerTextDiv {
        text-align: center;
        background-color: rgba(255, 255, 255, 0.5); /* White background with 50% opacity */
        padding: 10px;
        border-radius: 10px;
    }

    .headerText {
        color: black; /* Text color */
    }

    #gradient{
        position: relative;
        background-image: url('https://as1.ftcdn.net/v2/jpg/02/14/09/82/1000_F_214098258_VC0lJWUY5DWjqsnHF8JgYCrGy84MLcfh.jpg');
        width: 100%;
        min-height: 250px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        filter: brightness(95%); 
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

    <div id="container"></div>
    <div style="width: 100%; margin:0 auto; display: flex;">
        <button id="checkButton" style="max-width: 250px;">افحص الاجابه</button>
    </div>

    <script>
        class qsPoons {
            constructor(question, option1, option2, option3, answer) {
                this.question = question;
                this.option1 = option1;
                this.option2 = option2;
                this.option3 = option3;
                this.answer = answer;
            }
        }

        <?php
        require('connect.php');
        $sql = "SELECT * FROM questions";
        $result = $conn->query($sql);
        $qsList = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $question = new stdClass();
                $question->question = $conn->real_escape_string($row["question_text"]);
                $question->option1 = $conn->real_escape_string($row["option1"]);
                $question->option2 = $conn->real_escape_string($row["option2"]);
                $question->option3 = $conn->real_escape_string($row["option3"]);
                $question->answer = $conn->real_escape_string($row["correct_option"]);
                $qsList[] = $question;
            }
        }
        ?>

        const qsList = <?php echo json_encode($qsList); ?>;
        
        const container = document.querySelector('#container');
        const checkButton = document.querySelector('#checkButton');

        renderQuestions();

        checkButton.addEventListener("click", checkAnswer);

        function renderQuestions() {
            container.innerHTML = "";
            qsList.forEach((qs, index) => {
                const createDiv = document.createElement("div");
                const createH1 = document.createElement('h1');
                createDiv.classList.add("soso");
                createH1.innerHTML = qs.question;

                const createOption = (option, label) => {
                    const createDivOption = document.createElement("div");
                    const createInput = document.createElement('input');
                    const createPText = document.createElement('span');
                    createInput.setAttribute("type", "radio");
                    createInput.setAttribute("value", option);
                    createInput.setAttribute("name", index);
                    createPText.innerHTML = label;
                    createDivOption.appendChild(createInput);
                    createDivOption.appendChild(createPText);
                    return createDivOption;
                };

                createDiv.appendChild(createH1);
                createDiv.appendChild(createOption(qs.option1, qs.option1));
                createDiv.appendChild(createOption(qs.option2, qs.option2));
                createDiv.appendChild(createOption(qs.option3, qs.option3));
                container.appendChild(createDiv);
            });
        }

        function checkAnswer() {
            let score = 0;
            for (let i = 0; i < qsList.length; i++) {
                const options = document.getElementsByName(i);
                for (let j = 0; j < options.length; j++) {
                    if (options[j].checked && options[j].value === qsList[i].answer) {
                        score++;
                        break;
                    }
                }
            }
            let threshold = qsList.length * 0.7;

            // Check if the score is greater than the threshold
            if (score > threshold) {
                alert("Wow, you are so smart!");
            } else {
                alert(":) Try again");
            }
            alert("Your score is " + score + "/" + qsList.length);
        }
    </script>
</body>
</html>
