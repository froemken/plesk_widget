# Gemini-Specific Interaction Rules

This file complements AGENTS.md with instructions specific to the Gemini LLM interaction.

## 1. Context Handling
- When analyzing this extension, prioritize the structures defined in `Configuration/TCA/` and `ext_localconf.php` to understand the extension's footprint.
- Always check for the existence of `Build/Scripts/runTests.sh` before suggesting test execution commands.

## 2. Response Optimization
- **Code First:** When asked for a solution, provide the English code block first, followed by a brief German explanation.
- **Diffs over Full Files:** Unless requested otherwise, provide code changes as partial snippets or diffs to save context tokens.
- **Strict Grammar:** In German replies, use commas for sub-clauses and avoid dashes (Gedankenstriche). Use closed compounding (e.g., "Pappdosen").

## 3. Tool Usage
- If Gemini has access to the web/search tool: Always verify the latest TYPO3 14.x documentation for Breaking Changes before suggesting Core API usage.
- Use the `image_generation` tool only if explicitly asked to visualize architecture or UI mockups.

## 4. Maintenance
- If you notice a conflict between this file and the code's current state, point it out to the user immediately.
