import { defineConfig } from 'vitepress'

export default defineConfig({
  lang: 'en-US',
  title: 'Laravel AI Costs',
  description: 'Cost tracking for Laravel AI agents — calculate API costs from usage metadata across providers.',

  base: '/laravel-ai-costs/',
  cleanUrls: true,
  lastUpdated: true,

  head: [
    ['link', { rel: 'icon', type: 'image/webp', href: '/logo.webp' }],
    ['meta', { name: 'theme-color', content: '#C8932F' }],
    ['meta', { property: 'og:type', content: 'website' }],
    ['meta', { property: 'og:title', content: 'Laravel AI Costs' }],
    ['meta', {
      property: 'og:description',
      content: 'Cost tracking for Laravel AI agents — calculate API costs from usage metadata across providers.',
    }],
    ['meta', { property: 'og:image', content: '/logo.webp' }],
  ],

  themeConfig: {
    logo: '/logo.webp',
    siteTitle: 'Laravel AI Costs',

    nav: [
      { text: 'Guide', link: '/guide/getting-started', activeMatch: '/guide/' },
      { text: 'API', link: '/api/calculator', activeMatch: '/api/' },
      {
        text: 'v1.0.0',
        items: [
          { text: 'Changelog', link: 'https://github.com/aaix/laravel-ai-costs/blob/main/CHANGELOG.md' },
          { text: 'Packagist', link: 'https://packagist.org/packages/aaix/laravel-ai-costs' },
        ],
      },
    ],

    sidebar: {
      '/guide/': [
        {
          text: 'Introduction',
          items: [
            { text: 'Getting started', link: '/guide/getting-started' },
            { text: 'Installation', link: '/guide/installation' },
          ],
        },
        {
          text: 'Usage',
          items: [
            { text: 'TracksAiCost trait', link: '/guide/trait' },
            { text: 'Direct calculator', link: '/guide/calculator' },
            { text: 'Configuration', link: '/guide/configuration' },
            { text: 'Pricing resolution', link: '/guide/pricing-resolution' },
          ],
        },
      ],
      '/api/': [
        {
          text: 'API reference',
          items: [
            { text: 'AiCostCalculator', link: '/api/calculator' },
            { text: 'AiCostResult', link: '/api/cost-result' },
            { text: 'TracksAiCost', link: '/api/tracks-ai-cost' },
            { text: 'LitellmPricingProvider', link: '/api/litellm-provider' },
          ],
        },
      ],
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/aaix/laravel-ai-costs' },
    ],

    editLink: {
      pattern: 'https://github.com/aaix/laravel-ai-costs/edit/main/docs/:path',
      text: 'Edit this page on GitHub',
    },

    search: {
      provider: 'local',
    },

    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright © 2026 Jonas Gnioui',
    },
  },
})
