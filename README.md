# GPMS — Graduation Projects Management System

> نظام ذكي لإدارة مراحل مشاريع التخرج باستخدام خوارزميات الذكاء الاصطناعي

A full-stack academic platform for managing graduation projects end-to-end, powered by **7+ AI algorithms** for text similarity, classification, fair distribution, smart scheduling, and sentiment analysis.

![Laravel](https://img.shields.io/badge/Laravel-12.54.1-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2.12-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)
![Node.js](https://img.shields.io/badge/Node.js-20-339933?logo=node.js&logoColor=white)
![Express](https://img.shields.io/badge/Express-4-000000?logo=express&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white)
![Socket.io](https://img.shields.io/badge/Socket.io-4-010101?logo=socket.io&logoColor=white)

---

## Overview

Graduation projects are one of the most critical academic requirements. In most universities, managing them relies on manual, fragmented processes — leading to duplicated ideas, unfair distribution, poor communication, and lost archives.

**GPMS** solves this by providing a unified, AI-powered platform with dedicated interfaces for **Students**, **Supervisors**, and **Admins**.

Key highlights:

- **7+ classical AI algorithms** implemented from scratch (no external AI APIs)
- **Two-server architecture** — Laravel for business logic, Node.js for AI services
- **Real-time notifications** via WebSockets
- **Arabic NLP support** with custom stop words and keyword extraction
- **3 role-based interfaces** with full access control
- **Smart archive** with semantic search and visual analytics

---

## Features

### For Students

| Feature | Description |
|---------|-------------|
| **Project Registration** | Create project with title, description, team, and preferences |
| **AI Assistant** | Generates 5-step action plan, checks uniqueness, suggests supervisors |
| **Supervisor Preferences** | Rank preferred supervisors by priority |
| **Project Progress** | Track milestones and upload deliverables |
| **Notes Map** | Visual "Project Notes Map" with per-note sentiment analysis |
| **Real-time Notifications** | Instant updates when supervisor adds notes or approves |

### For Supervisors

| Feature | Description |
|---------|-------------|
| **Project Review** | Approve, reject, or request modifications |
| **Similarity Detection** | Auto-detects similar titles across archive (60% threshold) |
| **AI Analysis** | Neural network evaluates project quality + readiness score |
| **Grade Submission** | Per-milestone grading with detailed feedback |
| **Notes System** | Add notes, students receive instant notifications |
| **Discussion Scheduling** | Auto-schedule or manual with conflict detection |

### For Admins

| Feature | Description |
|---------|-------------|
| **User Management** | Create/delete students, supervisors, admins |
| **Fair Distribution** | One-click Gale-Shapley distribution across supervisors |
| **Smart Scheduling** | A* algorithm generates conflict-free discussion schedule |
| **Archive Management** | Add/edit/delete archived projects |
| **Statistics Dashboard** | KPIs, doughnut charts, bar charts per department |
| **Visual Analysis** | PCA + K-Means scatter plot of all projects |
| **System Settings** | Configure max projects per supervisor, team size, deadlines |
| **Announcements** | Broadcast general or project-specific announcements |

---

## AI & Machine Learning Algorithms

Seven classical algorithms implemented from scratch — no external AI APIs:

### 1. Text Similarity (3 algorithms)

| Algorithm | Accuracy | Use Case |
|-----------|----------|----------|
| **Jaro-Winkler** | 100% | Short Arabic titles in archive comparison |
| **Cosine Similarity** | 80% | Project descriptions with TF-IDF + stop words removal |
| **Levenshtein Distance** | 50% | Near-duplicate detection in supervisor review |

### 2. Perceptron Neural Network (Project Classification)

- **Architecture:** 50 → 25 → 5 (Multi-Layer Perceptron)
- **Input:** TF-IDF vectors with stop words removal + department-specific keywords
- **Output:** Department classification (Information Systems, Communications, Computer Systems)
- **Accuracy improvement:** 40% → **58%** after adding TF-IDF, stop words, and distinctive keywords
- **Trained on:** 159 archived projects (53 per department, balanced dataset)
- **Response time:** < 0.5 seconds

### 3. Gale-Shapley (Fair Distribution)

- Distributes projects to supervisors based on student preferences + supervisor capacity
- **100% distribution by academic specialization**
- 46% match with student preferences (limited by specialization constraints)
- 12/22 projects used fallback plan (supervisor capacity exceeded)
- **Execution time:** < 0.5 seconds

### 4. A* Algorithm (Smart Discussion Scheduling)

- Finds optimal schedule satisfying **3 constraint levels**:
  - Supervisor (no overlap for same supervisor)
  - Students (no overlap for team members)
  - Room (no double-booking)
- 30-minute buffer window before/after each discussion
- **100% conflict-free results**
- **Execution time:** < 3 seconds for all unscheduled projects

### 5. Conflict Detection (Assistant for Manual Scheduling)

- Real-time validation before saving any new schedule
- Checks 3 levels: supervisor, students, room
- **Time complexity:** O(k) where k = team size
- **Instant feedback** in UI

### 6. Sentiment Analysis (Arabic Notes)

- **Lexicon-based approach** with 80+ positive and 60+ negative Arabic words
- Classifies notes as: positive, negative, or neutral
- **45% agreement with human evaluation** (limited by small lexicon)
- **Instant execution** (< 0.1 seconds)

### 7. PCA + K-Means (Visual Analysis)

- **PCA** reduces text features to 2D (78% variance explained)
- **K-Means** groups projects into 3 thematic clusters (k=4)
- **Silhouette Score:** 0.72 (good clustering quality)
- **Use case:** Discover trending topics, identify outliers, plan discussion panels

---

## Tech Stack

### Backend (Laravel)

- **Laravel 12.54.1** — MVC framework
- **PHP 8.2.12** — server language
- **MySQL 8** — database
- **Eloquent ORM** — database abstraction
- **Laravel Breeze** — authentication
- **Blade** — templating engine

### Frontend

- **Blade templates** — server-side rendering
- **Bootstrap 5** — UI framework
- **Tailwind CSS 3** — utility styling
- **Vanilla JS** — interactions
- **Cairo + FontAwesome** — Arabic typography + icons

### AI Server (Node.js)

- **Node.js + Express** — separate AI microservice on port 3000
- **Synaptic.js** — neural network library
- **Custom implementations** — Levenshtein, Jaro-Winkler, Cosine, Gale-Shapley, A*, K-Means, PCA

### Real-time

- **Socket.io** — bidirectional WebSocket communication
- **Real-time notifications** for schedules, notes, approvals

---

## Architecture

```
┌─────────────────────────────────────────────────────┐
│                   Browser                           │
│         Blade + Bootstrap + Tailwind + JS           │
└──────────────┬──────────────────────────────────────┘
               │  HTTP (REST)  +  WebSocket
               ▼
┌─────────────────────────────────────────────────────┐
│             Laravel Application (PHP 8.2)           │
│                     (Port 8000)                     │
│                                                     │
│  • MVC Architecture    • Eloquent ORM               │
│  • Authentication      • Role-based Access          │
│  • Real-time Events    • RESTful Client             │
└──────┬──────────────────────────┬───────────────────┘
       │                          │
       │ HTTP POST                │ Eloquent
       ▼                          ▼
┌──────────────────────┐  ┌──────────────────────────┐
│   Node.js AI Server  │  │    MySQL Database        │
│     (Port 3000)      │  │   • users                │
│                      │  │   • projects             │
│  • Perceptron (MLP)  │  │   • proposals            │
│  • Text Similarity   │  │   • supervisor_prefs     │
│  • Gale-Shapley      │  │   • notes                │
│  • A* Scheduling     │  │   • evaluations          │
│  • Sentiment (Lex)   │  │   • archived_projects    │
│  • PCA + K-Means     │  │   • notifications        │
└──────────────────────┘  └──────────────────────────┘
```

---

## Screenshots

> Visual walkthrough of the three role-based interfaces.

### Student Interface

| Page | Description |
|------|-------------|
| Welcome | Landing page with login/register |
| Dashboard | Main control center with project card |
| New Project | 2-stage form: title/description + team + supervisor preferences |
| AI Assistant | Auto action plan + uniqueness check |
| Notes Map | Visual project notes with sentiment analysis |
| Project Progress | Milestone tracking with file uploads |
| Schedule | Discussion dates and locations |

### Supervisor Interface

| Page | Description |
|------|-------------|
| Dashboard | All assigned projects with status cards |
| Project Review | Approve/reject with similarity alerts |
| AI Analysis | Neural network readiness score + recommendations |
| Notes Management | Add notes with real-time notification |
| Statistics | KPI dashboard with charts |

### Admin Interface

| Page | Description |
|------|-------------|
| Dashboard | System overview + statistics |
| User Management | Add/edit/delete users with role-based forms |
| Smart Distribution | Run Gale-Shapley + view results table |
| Smart Scheduling | Run A* + view conflict-free schedule |
| Archive | Search, filter, add/edit archived projects |
| Visual Analysis | PCA + K-Means scatter plot |
| Announcements | Broadcast general/project-specific news |
| Stop Requests | Manage supervisor stop-receive requests |

---

## Setup

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 20+ (for AI server)
- MySQL 8
- XAMPP (or similar)

### 1. Clone the repository

```bash
git clone https://github.com/nataliehammoud/YOUR-GPMS-REPO.git
cd YOUR-GPMS-REPO
```

### 2. Laravel Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Update `.env`:

```env
DB_DATABASE=gpms
DB_USERNAME=root
DB_PASSWORD=
AI_SERVER_URL=http://localhost:3000
```

```bash
php artisan migrate --seed
php artisan serve
```

### 3. Node.js AI Server Setup

```bash
cd ai-server
npm install
node server.js
```

The AI server runs on `http://localhost:3000` and exposes:

- `POST /check-similarity` — Text similarity detection
- `POST /classify` — Department classification (Perceptron)
- `POST /suggest-plan` — Action plan generation
- `POST /sentiment` — Arabic sentiment analysis
- `POST /schedule` — A* auto-scheduling

### 4. Access the system

Open: `http://localhost:8000`

---

## Design Decisions

### Why two separate servers (Laravel + Node.js)?

AI algorithms — especially the Perceptron neural network and A* scheduling — are **computationally intensive**. Running them inline in Laravel would:

- Block the main web server
- Slow down page loads
- Make the system hard to scale

By isolating them in a **Node.js microservice**, we ensure:

- Laravel remains fast and responsive
- AI algorithms can be updated without touching the main app
- The AI server can be independently scaled or replaced

### Why classical algorithms instead of LLMs?

For this specific problem:

| Task | Tool | Reason |
|------|------|--------|
| **Text similarity** | Jaro-Winkler + Cosine | Deterministic, explainable, instant |
| **Classification** | Perceptron MLP | Trains on 159 samples, no API needed |
| **Fair distribution** | Gale-Shapley | Mathematically proven stable matching |
| **Scheduling** | A* | Optimal with constraint satisfaction |
| **Sentiment** | Lexicon-based | Fast, offline, Arabic-aware |

LLMs would be **overkill** for these well-defined problems — slow, non-deterministic, expensive, and dependent on external APIs.

### Why Arabic-aware NLP?

The academic context is **Arabic-first**. Standard English NLP tools:

- Fail on Arabic morphology
- Miss context-specific terms
- Produce poor results

We built custom Arabic stop words, keyword lists per department, and RTL-aware preprocessing.

---

## Results & Evaluation

| Algorithm | Task | Result | Execution Time |
|-----------|------|--------|----------------|
| Jaro-Winkler | Text similarity | **100%** | Fast |
| Cosine Similarity | Text similarity | **80%** | Medium |
| Levenshtein | Text similarity | 50% | Fast |
| Perceptron (MLP) | Classification | **58%** | < 0.5s |
| Gale-Shapley | Fair distribution | **100%** | < 0.5s |
| A* | Auto scheduling | **100%** | < 3s |
| Conflict Detection | Manual scheduling helper | **100%** | Instant |
| Sentiment Analysis | Arabic notes | 45% | < 0.1s |
| PCA | Dimensionality reduction | 78% variance | < 2s |
| K-Means | Topic clustering | 0.72 Silhouette | < 1s |

**Key improvements:**

- Neural network accuracy: **40% → 58%** after adding TF-IDF, stop words, and department-specific keywords
- Balanced dataset (53 projects per department) removed bias toward "Information Systems"
- Full conflict-free scheduling achieved in **< 3 seconds**

---

## Author

**Natalie Hammoud**

Bachelor of Information Technology Engineering  
Faculty of Information & Communication Technology  
Tartous University — Syria

- GitHub: [@nataliehammoud](https://github.com/nataliehammoud)

**Academic Year:** 2025 / 2026

---

## License

This project is submitted as a graduation requirement for the Bachelor's degree in Information Technology Engineering.

© 2026 Natalie Hammoud. All rights reserved. Commercial use requires explicit permission.

---

If you find this project useful or interesting, consider giving it a star.
