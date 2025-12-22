---
title: "Stop Building Google Forms Manually: Generate Them Instantly with AI & GAS"
date: 2025-02-11 09:00:00
categories:
  - GAStips
tags:
  - GAS
  - ChatGPT
  - AI
  - Google Forms
  - Automation
status: publish
slug: ai-gas-form-generation-en
featured_image: ../images/thumbnails.png
---

Are you still manually clicking the "+" button to add questions one by one in Google Forms?

It's time to stop. **Let AI and Google Apps Script (GAS) do the work for you.**

1. **Ask AI (ChatGPT/Claude) to "design a perfect survey structure."**
2. **Ask AI to "output it as JSON."**
3. **Paste the JSON into GAS and run it.**

That's it. Even complex forms with dozens of questions can be **generated in seconds**.
Building forms manually is no longer a task for humans.

In this article, I'll share the **"Magic GAS Code"** that converts AI-generated JSON directly into a live Google Form.

---

## Step 1: Ask AI to Design the Structure

First, give a prompt like this to ChatGPT or Claude:

> "I need a job application form for software engineers. Please cover all necessary fields.
> Output the structure in the following JSON format:"

```json
{
  "title": "Form Title",
  "description": "Description",
  "sections": [
    {
      "title": "Section Name",
      "items": [
        { "type": "text", "title": "Question", "required": true },
        { "type": "multipleChoice", "title": "Select One", "choices": ["A", "B"] }
      ]
    }
  ]
}
```

The AI will act as an expert recruiter, designing high-quality questions and returning them as **programmatic JSON data**.

## Step 2: Paste into GAS and Run

Simply paste the AI-generated JSON into the `FORM_SCHEMA` variable in the GAS code below.

### The Magic Code (Copy & Paste)

```javascript
// ▼ Paste your AI-generated JSON here!
const FORM_SCHEMA = {
  title: "AI-Designed Engineer Application Form",
  description: "Generated automatically by ChatGPT.",
  sections: [
    {
      title: "Basic Info",
      items: [
        { type: "text", title: "Full Name", required: true },
        { type: "text", title: "Email Address", required: true },
        { type: "multipleChoice", title: "Role", choices: ["Frontend", "Backend", "SRE", "PM"] }
      ]
    },
    {
      title: "Skills & Experience",
      items: [
        { type: "checkbox", title: "Tech Stack", choices: ["React", "Vue", "Next.js", "Node.js", "Go", "Python", "AWS", "GCP"] },
        { type: "text", title: "Portfolio URL" },
        { type: "multipleChoice", title: "Years of Experience", choices: ["<1 year", "1-3 years", "3-5 years", "5+ years"] }
      ]
    }
  ]
};

// ▼ No need to touch the code below (Auto-generation logic)
function createFormFromSchema(schema) {
  const form = FormApp.create(schema.title);
  if (schema.description) form.setDescription(schema.description);

  schema.sections.forEach(section => {
    form.addSectionHeaderItem().setTitle(section.title);
    section.items.forEach(item => addItem(form, item));
  });

  Logger.log('✅ Form Created Successfully!');
  Logger.log('Edit URL: ' + form.getEditUrl());
  Logger.log('Published URL: ' + form.getPublishedUrl());
}

function addItem(form, item) {
  let q;
  switch (item.type) {
    case 'text':
      q = form.addTextItem();
      break;
    case 'paragraph':
      q = form.addParagraphTextItem();
      break;
    case 'multipleChoice':
      q = form.addMultipleChoiceItem();
      q.setChoices(item.choices.map(c => q.createChoice(c)));
      break;
    case 'checkbox':
      q = form.addCheckboxItem();
      q.setChoices(item.choices.map(c => q.createChoice(c)));
      break;
    case 'dropdown':
      q = form.addListItem();
      q.setChoices(item.choices.map(c => q.createChoice(c)));
      break;
    case 'date':
      q = form.addDateItem();
      break;
    default:
      return; // Skip unsupported types
  }

  q.setTitle(item.title);
  if (item.help) q.setHelpText(item.help);
  if (item.required) q.setRequired(true);
  if (item.other && (item.type === 'multipleChoice' || item.type === 'checkbox')) {
    q.setHasOtherOption(true);
  }
}

function run() {
  createFormFromSchema(FORM_SCHEMA);
}
```

## Why This is a Revolution

### 1. Edits become "Conversations"
"Add 'Other' to the choices." "Change the experience ranges."
Instead of clicking through menus, just tell the AI to "Update the JSON." Paste the new JSON, run the script, and you have a fresh form instantly.

### 2. Forms as Code
By saving the JSON, you can version control your forms. Need to recreate the exact survey from last year's event? It takes seconds if you have the JSON file.

### 3. Professional Quality for Everyone
Even if you don't know how to design a good survey, you can ask the AI to "think like a marketing expert and design a customer satisfaction survey." You get professional-grade questions ready to deploy.

## Summary

Building Google Forms is no longer a manual task.

**"Let AI design the structure, and let GAS build it."**

Once you adopt this workflow, the speed at which you can deploy surveys, applications, and feedback forms will change dramatically. Copy the code above and try it today.
