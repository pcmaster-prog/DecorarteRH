import { Badge } from "./Badge"

interface StatusBadgeProps {
  status: string
  label: string
  color?: string
}

function StatusBadge({ status, label, color }: StatusBadgeProps) {
  const colorMap: Record<string, string> = {
    green: "success",
    blue: "default",
    yellow: "warning",
    red: "destructive",
    gray: "secondary",
    orange: "warning",
    purple: "secondary",
  }

  return (
    <Badge variant={(colorMap[color || "gray"] as any) || "secondary"}>
      {label}
    </Badge>
  )
}

export { StatusBadge }
