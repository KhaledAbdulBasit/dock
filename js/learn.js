
    
  
      // Fade-in animation for cards
      const cards = document.querySelectorAll(".fade-in");
      cards.forEach((card, index) => {
          setTimeout(() => {
              card.style.opacity = "1";
              card.style.transform = "translateY(0)";
          }, index * 300);
      });
  
      // Load more articles function
      function loadMoreArticles() {
          const newArticles = [
              {
                  img: "img/6.jpg",
                  title: "How to improve your sleep?",
                  desc: "Tips for better sleep include: maintaining a consistent sleep schedule, creating a comfortable sleep environment, avoiding screens before bedtime, and practicing relaxation techniques."
              },
              {
                  img: "img/4.jpg",
                  title: "Mental Health and Its Importance",
                  desc: "Strategies for mental well-being: monitor your weight, stay active, eat healthily, engage in relaxation activities, manage stress, cultivate positive thoughts, and seek support when needed."
              },
              {
                  img: "img/5.jpg",
                  title: "Ways to prevent diabetes",
                  desc: "Diabetes prevention tips: maintain a healthy weight, exercise regularly, eat a balanced diet, limit sugar and saturated fats, avoid tobacco, and get regular health check-ups."
              },
              {
                  img: "img/2019_2_13_13_18_36_665.jpg",
                  title: "What is allergy?",
                  desc: "It is a reaction of the immune system of an allergic person to certain substances (such as pollen, mites, fungi, some foods, etc.) in the allergic person. The immune system usually fights harmful substances that enter the body, but in the case of allergies, it fights some substances as if they were harmful by producing antibodies that cause allergy symptoms."
              },
              {
                  img: "img/8.jpg",
                  title: "All about high blood pressure?",
                  desc: "High blood pressure is a common condition that occurs when there is constant pressure on the artery walls over a long period of time. It usually has no symptoms, but it can cause serious problems such as stroke, heart failure, and kidney failure."
              },
              {
                  img: "img/دكتور-مسالك-بولية-شاطر.webp",
                  title: "All about kidneys?",
                  desc: "Chronic kidney disease, also called chronic renal failure, involves the gradual loss of kidney function. The kidneys filter waste products and excess fluid from the blood, which can then be eliminated in the urine."
              },
          ];
  
          const container = document.getElementById("articles");
  
          newArticles.forEach(article => {
              const div = document.createElement("div");
              div.classList.add("article");
              div.innerHTML = `
                  <img src="${article.img}" alt="${article.title}">
                  <h2>${article.title}</h2>
                  <p>${article.desc}</p>
              `;
              container.appendChild(div);
          });
  
          document.getElementById("loadMore").style.display = "none";
      }

