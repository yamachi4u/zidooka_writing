# Gemini 3.1 Pro Preview Custom Tools

## Overview
Gemini 3.1 Pro Preview Custom Tools is a specialized variant of Google's Gemini 3.1 Pro model, released on February 19, 2026. It shares identical performance and pricing with the standard version but prioritizes the use of custom tools registered by developers.

## Key Difference from Standard Version
While the standard `gemini-3.1-pro-preview` model sometimes bypasses registered custom tools to execute direct bash commands, the Custom Tools variant ensures that developer-registered functions are prioritized and executed as intended.

## When to Use
- **AI Agent Development**: When using custom tools like `view_file`, `search_code`, or `edit_file`
- **DevOps Agents**: In environments mixing bash commands and custom tools
- **MCP Workflows**: For Model Context Protocol implementations requiring tool prioritization

## Performance and Pricing
- **Pricing**: $2.00 per million input tokens, $12.00 per million output tokens (identical to standard version)
- **Context Window**: 1,048,576 tokens
- **ARC-AGI-2 Score**: 77.1% (identical to standard version)

## How to Switch
To use the Custom Tools variant, simply change the model ID from `gemini-3.1-pro-preview` to `gemini-3.1-pro-preview-customtools`. This single-line change activates the tool-prioritization behavior.

## Use Case Guide
| Scenario | Recommended Model | Reason |
|----------|------------------|--------|
| Pure conversation/Q&A | Standard | Most stable output quality |
| Code generation (no tools) | Standard | Direct code output |
| Coding assistant (`view_file`, etc.) | Custom Tools | Ensures accurate tool calling |
| DevOps agent (bash + custom tools) | Custom Tools | Prevents tool bypassing |
| MCP workflows | Custom Tools | Guarantees tool priority |

## Official Guidance
Google's official statement: "If you are using gemini-3.1-pro-preview and the model ignores your custom tools in favor of bash commands, try the gemini-3.1-pro-preview-customtools model instead."

## Summary
Gemini 3.1 Pro Custom Tools is designed for AI agent development where custom tool reliability is critical. While maintaining identical performance to the standard version, it provides more predictable and secure tool execution behavior, making it ideal for production agent implementations.