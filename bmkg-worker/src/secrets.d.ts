// Secrets aren't declared in wrangler.jsonc (set via `wrangler secret put`
// instead), so `wrangler types` can't see them. This merges them into the
// generated `Env` interface from worker-configuration.d.ts.
interface Env {
  RESQ_WEBHOOK_API_KEY: string;
}
