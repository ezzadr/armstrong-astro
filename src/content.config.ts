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
    category: z.string().default('Car Keys'),
  }),
});

export const collections = { blog };
