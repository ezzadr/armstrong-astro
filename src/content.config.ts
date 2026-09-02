import { defineCollection, z } from 'astro:content';
import { glob } from 'astro/loaders';

const blog = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/blog' }),
  schema: z.object({
    title: z.string(),
    description: z.string(),
    pubDate: z.coerce.date().optional(),
    author: z.string().default('Rahim Ezzadpanah'),
    image: z.string().optional(),
    // Optional YouTube video for the post: accepts a bare video ID or any
    // youtube.com/watch, youtu.be, or /embed/ URL. Renders as a click-to-play
    // facade above the article and emits VideoObject schema for rich results.
    video: z.string().optional(),
    category: z.string().default('Car Keys'),
  }),
});

export const collections = { blog };
