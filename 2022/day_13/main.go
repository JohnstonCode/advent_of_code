package main

import (
	"encoding/json"
	"fmt"
	"os"
	"reflect"
	"sort"
	"strings"
)

func main() {
	content, _ := os.ReadFile("./input.txt")
	sum := 0
	var packets []any

	for i, pairs := range strings.Split(string(content), "\n\n") {
		pair := strings.Split(pairs, "\n")
		var left, right any

		_ = json.Unmarshal([]byte(pair[0]), &left)
		_ = json.Unmarshal([]byte(pair[1]), &right)

		packets = append(packets, left, right)

		if compare(left, right) <= 0 {
			sum += i + 1
		}
	}

	var div1, div2 any
	_ = json.Unmarshal([]byte("[[2]]"), &div1)
	_ = json.Unmarshal([]byte("[[6]]"), &div2)
	packets = append(packets, div1, div2)

	sort.Slice(packets, func(i, j int) bool {
		return compare(packets[i], packets[j]) < 0
	})

	dk := 1

	for i, v := range packets {
		str, _ := json.Marshal(v)

		if string(str) == "[[2]]" || string(str) == "[[6]]" {
			dk *= i + 1
		}
	}

	fmt.Println(sum)
	fmt.Println(dk)
}

func compare(left any, right any) int {
	if reflect.ValueOf(left).Kind() == reflect.Float64 && reflect.ValueOf(right).Kind() == reflect.Float64 {
		return int(left.(float64)) - int(right.(float64))
	}

	var leftSlice []any
	var rightSlice []any

	switch left.(type) {
	case []any, []float64:
		leftSlice = left.([]any)
	case float64:
		leftSlice = []any{left}
	}

	switch right.(type) {
	case []any, []float64:
		rightSlice = right.([]any)
	case float64:
		rightSlice = []any{right}
	}

	for i := range leftSlice {
		if len(rightSlice) <= i {
			return 1
		}

		if cmp := compare(leftSlice[i], rightSlice[i]); cmp != 0 {
			return cmp
		}
	}

	if len(leftSlice) == len(rightSlice) {
		return 0
	}

	return -1
}
