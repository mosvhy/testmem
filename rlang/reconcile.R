library(dplyr)

# Read the datasets
csv_1 <- read.csv("rlang/csv_1.csv")
csv_2 <- read.csv("rlang/csv_2.csv")
csv_3 <- read.csv("rlang/csv_3.csv")

# Print column names to verify
print("CSV 1 columns:")
print(colnames(csv_1))

print("CSV 2 columns:")
print(colnames(csv_2))

print("CSV 3 columns:")
print(colnames(csv_3))

# Rename columns in csv_2 for consistency
csv_2 <- csv_2 %>%
  rename(vend_ref_id = vend_ref_no)  # Renaming vend_ref_no to vend_ref_id

# Merge csv_1 and csv_2 using vend_ref_id
merged_df_1_2 <- csv_1 %>%
  full_join(csv_2, by = "vend_ref_id", suffix = c(".1", ".2"))

# Merge the result with csv_3 using aggrgt_no
reconciliation_df <- merged_df_1_2 %>%
  full_join(csv_3, by = "aggrgt_no")

# Add flags/columns to indicate differences
reconciliation_df <- reconciliation_df %>%
  mutate(
    empty_on_csv_1 = ifelse(is.na(amount.1), "empty_on_csv_1", ""),
    empty_on_csv_2 = ifelse(is.na(amount.2), "empty_on_csv_2", ""),
    empty_on_csv_3 = ifelse(is.na(amount), "empty_on_csv_3", ""),
    difference_amount = ifelse(!is.na(amount.1) & !is.na(amount.2) & !is.na(amount) & 
                               (amount.1 != amount.2 | amount.2 != amount), "difference_amount", ""),
    difference_flag = ifelse(is.na(amount.1) | is.na(amount.2) | is.na(amount) | 
                             amount.1 != amount.2 | amount.2 != amount, "Difference amount", "Match")
  )

# Combine the flags into a single column
reconciliation_df <- reconciliation_df %>%
  mutate(difference_details = paste(empty_on_csv_1, empty_on_csv_2, empty_on_csv_3, difference_amount, sep = ", ")) %>%
  mutate(difference_details = gsub(", $", "", difference_details))  # Remove trailing comma

# Print the merged dataframe with the difference flag
print("Reconciliation DataFrame with Differences:")
print(head(reconciliation_df))