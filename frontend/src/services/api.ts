type ApiResult<T> = {
  ok: boolean;
  message?: string;
} & T;

async function request<T>(url: string, init?: RequestInit): Promise<ApiResult<T>> {
  const response = await fetch(url, {
    credentials: "include",
    headers: {
      "Content-Type": "application/json",
      ...(init?.headers ?? {}),
    },
    ...init,
  });

  const contentType = response.headers.get("content-type") || "";
  if (!contentType.toLowerCase().includes("application/json")) {
    throw new Error(
      "API indisponivel neste ambiente. Verifique backend/proxy de /api antes de logar."
    );
  }

  const data = (await response.json()) as ApiResult<T>;
  if (!response.ok) {
    throw new Error(data.message || "Falha na requisicao.");
  }
  return data;
}

export type AuthUser = {
  id: number;
  username: string;
};

export type DashboardSummary = {
  database: string;
  totalUsers: number;
  recentUsers: Array<{ username: string; created_at: string }>;
};

export async function getCsrfToken(): Promise<string> {
  const data = await request<{ csrf_token: string }>("/api/csrf", { method: "GET" });
  return data.csrf_token;
}

export async function getCurrentUser(): Promise<AuthUser> {
  const data = await request<{ user: AuthUser }>("/api/auth/me", { method: "GET" });
  return data.user;
}

export async function login(payload: {
  username: string;
  password: string;
  csrfToken: string;
}): Promise<AuthUser> {
  const data = await request<{ user: AuthUser }>("/api/auth/login", {
    method: "POST",
    body: JSON.stringify({
      username: payload.username,
      password: payload.password,
      csrf_token: payload.csrfToken,
    }),
  });
  return data.user;
}

export async function logout(csrfToken: string): Promise<void> {
  await request<Record<string, never>>("/api/auth/logout", {
    method: "POST",
    body: JSON.stringify({ csrf_token: csrfToken }),
  });
}

export async function getDashboardSummary(): Promise<DashboardSummary> {
  const data = await request<{ summary: DashboardSummary }>("/api/dashboard/summary", {
    method: "GET",
  });
  return data.summary;
}
